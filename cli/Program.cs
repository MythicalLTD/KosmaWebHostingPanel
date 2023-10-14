using System;
using System.Diagnostics.CodeAnalysis;
using YamlDotNet.Serialization;

namespace KosmaPanel
{

    public class Program
    {
        public static Logger logger = new Logger();

        public static string ascii = @" 
  _  __                           _____                 _ 
 | |/ /                          |  __ \               | |
 | ' / ___  ___ _ __ ___   __ _  | |__) |_ _ _ __   ___| |
 |  < / _ \/ __| '_ ` _ \ / _` | |  ___/ _` | '_ \ / _ \ |
 | . \ (_) \__ \ | | | | | (_| | | |  | (_| | | | |  __/ |
 |_|\_\___/|___/_| |_| |_|\__,_| |_|   \__,_|_| |_|\___|_|

";
        public static string version = "1.0.0";
        public static bool skiposcheck = false;
        public static ConfigHandler cfg_handler = new ConfigHandler();
        public static Debug dbg = new Debug();
        public static Encryption encryption = new Encryption();
        public static IConsole iconsole = new IConsole();
        public static Database db = new Database();
        public static Migrate mg = new Migrate();
        public static RemoveTrailingDots rmt = new RemoveTrailingDots();
        public static PluginLoader pl = new PluginLoader();

        [RequiresAssemblyFiles()]
        public static void Main(string[] args)
        {
            Console.Clear();
            Console.WriteLine(ascii);
            pl.LoadPlugins();
            if (skiposcheck == false)
            {
                if (!System.OperatingSystem.IsLinux())
                {
                    logger.Log(LogType.Error, "Sorry but this app runs on linux!");
                    Environment.Exit(0x0);
                }
            }
            if (args.Contains("-generate-config"))
            {
                try
                {
                    cfg_handler.CreateConfig();
                    Environment.Exit(0x0);
                }
                catch (Exception ex)
                {
                    logger.Log(LogType.Error, "Failed to create config: " + ex.Message);
                }
                logger.Log(LogType.Info, "Configuration file generated.");
                Environment.Exit(0x0);
            }
            else if (args.Contains("-delete-config"))
            {
                logger.Log(LogType.Info,"Wow, buddy, this command shall be run only if you know what it does.");
                logger.Log(LogType.Info,"Are you sure you want to proceed? (yes/no)");
                #pragma warning disable
                string userResponse = Console.ReadLine().Trim().ToLower();
                if (userResponse == "yes")
                {
                    try
                    {
                        cfg_handler.DeleteConfig();
                        Environment.Exit(0x0);
                    }
                    catch (Exception ex)
                    {
                        logger.Log(LogType.Error, "Failed to delete config: " + ex.Message);
                        Environment.Exit(0x0);
                    }
                }
                #pragma warning restore
                else if (userResponse == "no")
                {
                    logger.Log(LogType.Info,"Action cancelled.");
                    Environment.Exit(0x0);
                }
                else
                {
                    logger.Log(LogType.Info,"Invalid response. Please enter 'yes' or 'no'.");
                    Environment.Exit(0x0);
                }
            }
            else if (args.Contains("-key-generate"))
            {
                logger.Log(LogType.Info,"Wow, buddy, this command shall be run only once, and that's when you set up the dashboard. Please do not run this command if you don't know what it does or if you have users in your database.");
                logger.Log(LogType.Info,"Are you sure you want to proceed? (yes/no)");
                #pragma warning disable
                string userResponse = Console.ReadLine().Trim().ToLower();
                if (userResponse == "yes")
                {
                    try
                    {
                        encryption.generatekey();
                        Environment.Exit(0x0);
                    }
                    catch (Exception ex)
                    {
                        logger.Log(LogType.Error, "Failed to delete config: " + ex.Message);
                        Environment.Exit(0x0);
                    }
                }
                #pragma warning restore
                else if (userResponse == "no")
                {
                    logger.Log(LogType.Info,"Action cancelled.");
                    Environment.Exit(0x0);
                }
                else
                {
                    logger.Log(LogType.Info,"Invalid response. Please enter 'yes' or 'no'.");
                    Environment.Exit(0x0);
                }
            }
            else if (args.Contains("-enable-debug"))
            {
                try
                {
                    dbg.enable();
                    Environment.Exit(0x0);
                }
                catch (Exception ex)
                {
                    logger.Log(LogType.Error, "Failed to update config: " + ex.Message);
                    Environment.Exit(0x0);
                }
            }
            else if (args.Contains("-enable-console"))
            {
                try
                {
                    iconsole.enable();
                    Environment.Exit(0x0);
                }
                catch (Exception ex)
                {
                    logger.Log(LogType.Error, "Failed to update config: " + ex.Message);
                    Environment.Exit(0x0);
                }
            }
            else if (args.Contains("-disable-console"))
            {
                try
                {
                    iconsole.disable();
                    Environment.Exit(0x0);
                }
                catch (Exception ex)
                {
                    logger.Log(LogType.Error, "Failed to delete config: " + ex.Message);
                    Environment.Exit(0x0);
                }
            } 
            else if (args.Contains("-disable-debug"))
            {
                try
                {
                    dbg.disable();
                    Environment.Exit(0x0);
                }
                catch (Exception ex)
                {
                    logger.Log(LogType.Error, "Failed to update config: " + ex.Message);
                    Environment.Exit(0x0);
                }
            }
            
            else if (args.Contains("-version"))
            {
                logger.Log(LogType.Info, "You are running version: " + version);
                Environment.Exit(0x0);
            }
            else if (args.Contains("-config-database")) {
                db.Configurator();
                Environment.Exit(0x0);
            }
            else if (args.Contains("-migrate-database-now")) {
                mg.Now();
                Environment.Exit(0x0);
            }
            else if (args.Contains("-help")) {
                Console.Clear();
                Console.WriteLine("╔≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡⊳ KosmaPanel CLI ⊲≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡╗");
                Console.WriteLine("‖                                                                                                          ‖");
                Console.WriteLine("‖    -help | Opens a help menu with the available commands.                                                ‖");
                Console.WriteLine("‖    -generate-config | Generate a new config file for KosmaPanel.                                         ‖");
                Console.WriteLine("‖    -delete-config | Delete the config file for KosmaPanel.                                               ‖");
                Console.WriteLine("‖    -key-generate | Generate a new encryption key for KosmaPanel.                                         ‖");
                Console.WriteLine("‖    -enable-debug | Enables the debug mode to display error messages for KosmaPanel.                      ‖");
                Console.WriteLine("‖    -disable-debug | Disables the debug mode to hide error messages for KosmaPanel.                       ‖");
                Console.WriteLine("‖    -config-database | Add the database connection to your config file.                                   ‖");
                Console.WriteLine("‖    -migrate-database-now | Create and setup all tables in the database                                   ‖");
                Console.WriteLine("‖    -version | See the version / build version of the CLI.                                                ‖");
                Console.WriteLine("‖                                                                                                          ‖");
                Console.WriteLine("╚≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡⊳ Copyright 2023 MythicalSystems ⊲≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡╝");                
                Environment.Exit(0x0);
            }
            else if (args.Length > 0)
            {
                logger.Log(LogType.Error, "This is an invalid startup argument. Please use '-help' to get more information");
                Environment.Exit(0x0);
            }
            else
            {
                logger.Log(LogType.Error, "This is an invalid startup argument. Please use '-help' to get more information");
                Environment.Exit(0x0);
            }
        }

    }

}