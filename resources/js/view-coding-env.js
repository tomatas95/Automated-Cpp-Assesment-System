document.addEventListener('DOMContentLoaded', function() {
    let editor = ace.edit("editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/c_cpp");
    editor.setReadOnly(true);

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

    document.getElementById('toggleThemeBtn').addEventListener('click', function() {
        let currentTheme = editor.getTheme();
        let newTheme = currentTheme === "ace/theme/dracula" ? "ace/theme/eclipse" : "ace/theme/dracula";
        editor.setTheme(newTheme);

        showPopup('theme', popupi18.themepopup);
    });

    function showPopup(type, message) {
        const allPopups = document.querySelectorAll('.popup');
        allPopups.forEach(popup => {
            popup.style.display = 'none';
        });

        let popupId;
        switch (type) {
            case 'run':
                popupId = 'runPopup';
                break;
            case 'theme':
                popupId = 'themePopup';
                break;
            default:
                return;
        }

        const popup = document.getElementById(popupId);
        if (popup) {
            const popupContent = popup.querySelector('.popup-content');
            popupContent.innerText = message;
            popup.style.display = 'block';

            setTimeout(() => {
                popup.style.display = 'none';
            }, 3000);
        }
    }

    function displayInput(variables, coutMessage, callback) {
        const inputContainer = document.getElementById('input-container');
        const inputFields = document.getElementById('input-fields');
        const submitButton = document.getElementById('submitInputs');

        inputFields.innerHTML = '';

        variables.forEach((variable, index) => {
            const message = document.createElement('p');
            message.innerText = coutMessage[index] || `Enter ${variable}:`;
            inputFields.appendChild(message);

            const inputDiv = document.createElement('div');
            inputDiv.innerHTML = `<input type="text" id="input_${variable}" class="form-control mb-2" placeholder="${popupi18.variable_placeholder}" />`;
            inputFields.appendChild(inputDiv);
        });

        inputContainer.style.display = 'block';
        submitButton.onclick = function() {
            const inputs = [];
            variables.forEach(variable => {
                const input = document.getElementById(`input_${variable}`).value;
                inputs.push(input);
            });

            inputContainer.style.display = 'none';
            callback(inputs);
        };
    }

    function clearPreviousOutputAndInputs() {
        const outputElement = document.getElementById('console-output');
        outputElement.innerHTML = '';

        const inputFields = document.getElementById('input-fields');
        inputFields.innerHTML = '';

        const inputContainer = document.getElementById('input-container');
        inputContainer.style.display = 'none';
    }

    function extractCoutMessages(code) {
        const coutRegex = /(?:std::)?cout\s*<<\s*("[^"]*")\s*;/g;
        let match;
        let messages = [];

        while ((match = coutRegex.exec(code)) !== null) {
            messages.push(match[1].replace(/"/g, ''));
        }

        return messages;
    }

    function filterFinalOutput(finalOutput, coutMessage) {
        let filteredOutput = finalOutput;

        coutMessage.forEach(message => {
            const coutRegex = new RegExp(message, 'g');
            filteredOutput = filteredOutput.replace(coutRegex, '');
        });

        return filteredOutput.trim();
    }

    document.getElementById('runBtn').addEventListener('click', function() {
        showPopup('run', popupi18.runpopup);

        clearPreviousOutputAndInputs();

        const code = ace.edit("editor").getValue();
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const cinRegex = /(?:std::)?cin\s*>>\s*([^\s;]+(?:\s*>>\s*[^\s;]+)*)/g;
        let match;
        let variables = [];
        while ((match = cinRegex.exec(code)) !== null) {
            let vars = match[1].split(/\s*>>\s*/);
            variables.push(...vars);
        }

        const coutMessage = extractCoutMessages(code);

        if (variables.length > 0) {
            displayInput(variables, coutMessage, function(inputs) {
                const requestData = {
                    script: code,
                    language: 'cpp17',
                    versionIndex: '0',
                    stdin: inputs.join('\n'),
                    args: '',
                    hasInputFiles: false
                };

                const jDoodleAPI = '/proxy/jdoodle';

                fetch(jDoodleAPI, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(requestData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        const outputElement = document.getElementById('console-output');
                        if (data.statusCode === 200) {
                            const filteredOutput = filterFinalOutput(data.output, coutMessage);
                            outputElement.innerText = filteredOutput;
                            outputElement.setAttribute('data-exec-time', data.cpuTime);
                            outputElement.setAttribute('data-memory-usage', data.memory);
                        }
                    })
            });
        } else {
            const requestData = {
                script: code,
                language: 'cpp17',
                versionIndex: '0',
                stdin: '',
                args: '',
                hasInputFiles: false
            };

            const jDoodleAPI = '/proxy/jdoodle';

            fetch(jDoodleAPI, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .then(data => {
                    const outputElement = document.getElementById('console-output');
                    if (data.statusCode === 200) {
                        outputElement.innerText = data.output;
                        outputElement.setAttribute('data-exec-time', data.cpuTime);
                        outputElement.setAttribute('data-memory-usage', data.memory);
                    }
                })
        }
    });
});