using System.Diagnostics.CodeAnalysis;
using System.Reflection;
using Microsoft.CodeAnalysis;
using Microsoft.CodeAnalysis.CSharp;

namespace KosmaPanel
{
    public class PluginInfo
    {
        public required string Name { get; set; }
        public required string Version { get; set; }
        public required string Author { get; set; }
    }
    public enum PluginLogType
    {
        Error,
        Warning,
        Success
    }

    public class PluginLoader
    {
        private const string FolderPath = "plugins";

        public void CheckAndCreatePluginFolder()
        {
            if (!Directory.Exists(FolderPath))
            {
                Directory.CreateDirectory(FolderPath);
                Program.logger.Log(LogType.Info, "Plugin folder created successfully");
                CreatePluginDefault();
            }
        }

        private void CreatePluginDefault()
        {
            string folderPath = FolderPath;
            string filePath = Path.Combine(folderPath, "ExamplePlugin.cs"); // Use forward slash for path
            string code = @"
            using System;

            namespace KosmaPanelPlugin {
                public class PluginInit {
                    private string _name = ""ExamplePlugin"";
                    private string _version = ""1.0.0"";
                    private string _author = ""NaysKutzu"";
                    private Action<string> _logCallback;

                    public string Name {
                        get { return _name; }
                    }

                    public string Version {
                        get { return _version; }
                    }

                    public string Author {
                        get { return _author; }
                    }

                    public PluginInit(Action<string> logCallback) {
                        _logCallback = logCallback;
                        SayHello();
                    }

                    private void SayHello() {
                        _logCallback?.Invoke(""Hello, World from plugin!"");
                    }
                }
            }

            // WARNING: DO NOT EDIT THE NAMESPACE OR THE PluginInit name; this will not load on another pc!
            ";

            if (File.Exists(filePath))
            {
                return;
            }

            try
            {
                File.WriteAllText(filePath, code);
                Program.logger.Log(LogType.Info, "Default plugin created successfully");
            }
            catch (Exception e)
            {
                Program.logger.Log(LogType.Error, $"An error occurred: {e.Message}");
            }
        }

        [RequiresAssemblyFiles()]
        public void LoadPlugins()
        {
            CheckAndCreatePluginFolder();

            string[] files = Directory.GetFiles(FolderPath, "*.cs");
            foreach (string file in files)
            {
                string fileContent = File.ReadAllText(file);

                SyntaxTree syntaxTree = CSharpSyntaxTree.ParseText(fileContent);
                MetadataReference[] references = new MetadataReference[] {
                    MetadataReference.CreateFromFile(typeof(object).Assembly.Location),
                };

                CSharpCompilation compilation = CSharpCompilation.Create(
                    "PluginAssembly",
                    new[] { syntaxTree },
                    references,
                    new CSharpCompilationOptions(OutputKind.DynamicallyLinkedLibrary)
                );

                using (MemoryStream ms = new MemoryStream())
                {
                    var result = compilation.Emit(ms);
                    if (!result.Success)
                    {
                        foreach (Diagnostic diagnostic in result.Diagnostics)
                        {
                            if (diagnostic.IsWarningAsError || diagnostic.Severity == DiagnosticSeverity.Error)
                            {
                                string errorMessage = $"{Path.GetFileName(file)} - Line {diagnostic.Location.GetLineSpan().StartLinePosition.Line + 1}: {diagnostic.GetMessage()}";
                                Program.logger.Log(LogType.Error, errorMessage);
                            }
                        }
                    }
                    else
                    {
                        ms.Seek(0, SeekOrigin.Begin);
                        Assembly compiledAssembly = Assembly.Load(ms.ToArray());
                        #pragma warning disable
                        Action<string> logCallback = (message) => Program.logger.Log(LogType.Info, message);
                        Type pluginType = compiledAssembly.GetType("KosmaPanelPlugin.PluginInit");
                        object pluginInstance = Activator.CreateInstance(pluginType, logCallback);

                        // Call the Run method on the plugin instance
                        MethodInfo runMethod = pluginType.GetMethod("Run");
                        runMethod?.Invoke(pluginInstance, null);

                        PluginInfo pluginInfo = GetPluginInfo(pluginInstance);
                        Program.logger.Log(LogType.Info, $"Loaded plugin: {pluginInfo.Name} (Version: {pluginInfo.Version}, Author: {pluginInfo.Author})");
                        #pragma warning restore
                    }
                }
            }
        }
        private PluginInfo GetPluginInfo(object pluginInstance)
        {
            Type pluginType = pluginInstance.GetType();
            #pragma warning disable
            PropertyInfo nameProperty = pluginType.GetProperty("Name");
            PropertyInfo versionProperty = pluginType.GetProperty("Version");
            PropertyInfo authorProperty = pluginType.GetProperty("Author");

            PluginInfo pluginInfo = new PluginInfo
            {
                Name = nameProperty?.GetValue(pluginInstance)?.ToString(),
                Version = versionProperty?.GetValue(pluginInstance)?.ToString(),
                Author = authorProperty?.GetValue(pluginInstance)?.ToString()
            };
            #pragma warning restore
            return pluginInfo;
        }
    }
}

