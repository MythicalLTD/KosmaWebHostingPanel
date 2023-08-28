using System;

namespace KosmaPanelPlugin {
    public class PluginInit {
        private string _name = "ExamplePlugin";
        private string _version = "1.0.0";
        private string _author = "NaysKutzu";
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
        }

        public void Run() {
            SayHello();
        }

        private void SayHello() {
            _logCallback?.Invoke("Hello, World from plugin!");
        }
    }
}
