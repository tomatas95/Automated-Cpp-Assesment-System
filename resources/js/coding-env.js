document.addEventListener('DOMContentLoaded', function() {
    let editor = ace.edit("editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/c_cpp");
    editor.setValue(`#include <iostream>
using namespace std;

int main() {
    // ${popupi18.terminal_code}
    return 0;
}`);
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableLiveAutocompletion: true,
        enableSnippets: true,
        useSoftTabs: true,
        displayIndentGuides: true,
        highlightActiveLine: true,
        highlightSelectedWord: true,
        wrap: true,
        showPrintMargin: false,
        showInvisibles: true,
        showGutter: true,
        behavioursEnabled: true,
        showFoldWidgets: true,
        fontSize: "14pt"
    });

    let toggleThemeBtn = document.getElementById('toggleThemeBtn');

    if (toggleThemeBtn) {
        toggleThemeBtn.addEventListener('click', function() {
            let currentTheme = editor.getTheme();
            let newTheme = currentTheme === "ace/theme/dracula" ? "ace/theme/eclipse" : "ace/theme/dracula";
            editor.setTheme(newTheme);
        });
    }
});