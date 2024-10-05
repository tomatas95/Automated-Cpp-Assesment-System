document.addEventListener('DOMContentLoaded', function() {
    const editor = ace.edit("editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/c_cpp");

    if (codeSolution) {
        editor.setValue(codeSolution)
    } else {
        editor.setValue(`#include <iostream>
using namespace std;
            
int main() {
    // ${popupi18.terminal_code}
    return 0;
}`);
    }
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

    document.getElementById('createForm').addEventListener('submit', function(event) {
        event.preventDefault();

        document.getElementById('code_solution').value = editor.getValue();
        const code = editor.getValue();
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const checkInputs = [];
        const checkInputValues = [
            document.querySelectorAll('input[name="check1[]"]'),
            document.querySelectorAll('input[name="check2[]"]'),
            document.querySelectorAll('input[name="check3[]"]')
        ];

        checkInputValues.forEach(function(inputElements) {
            let combinedInputs = '';
            inputElements.forEach(function(inputElement) {
                combinedInputs += inputElement.value.trim() + ' ';
            });
            checkInputs.push(combinedInputs.trim());
        });

        const expectedAnswers = [
            document.querySelector('input[name="check1_answer"]').value.trim(),
            document.querySelector('input[name="check2_answer"]').value.trim(),
            document.querySelector('input[name="check3_answer"]').value.trim()
        ];

        const coutMessage = extractCoutMessages(code);
        const checkResults = [];

        checkInputs.forEach(function(input, index) {
            let modifiedCode = replaceHardcodedValues(code, input);

            sendCode(modifiedCode, input, token, expectedAnswers[index], index, checkResults, checkInputs.length, coutMessage);
        });
    });

    function replaceHardcodedValues(code, checkInput) {
        let modifiedCode = code;
        let inputValues = checkInput.split(' ');

        const arrayPattern = /(int|float|double|char|string|long|unsigned|signed|(?:int|char)\s*\*)\s+(var\w*)\s*\[\s*\]\s*=\s*\{[^}]+\};/g;
        const variablePattern = /(int|float|double|char|string|long|unsigned|signed|(?:int|char)\s*\*)\s+(var\w*)\s*=\s*[^;]+;/g;

        let index = 0;

        modifiedCode = modifiedCode.replace(variablePattern, function(match, varType, varName) {
            if (index < inputValues.length) {
                let newValue = inputValues[index];
                index++;

                if (varType === 'string' || varType === 'char') {
                    return `${varType} ${varName} = "${newValue}";`;
                } else {
                    return `${varType} ${varName} = ${newValue};`;
                }
            }
            return match;
        });

        modifiedCode = modifiedCode.replace(arrayPattern, function(match, varType, varName) {
            if (index < inputValues.length) {
                const arraySize = parseInt(inputValues[0]);
                const arrayElements = inputValues.slice(1, arraySize + 1).join(', ');
                index += arraySize;

                return `${varType} ${varName}[] = {${arrayElements}};`;
            }
            return match;
        });

        return modifiedCode;
    }



    function sendCode(code, input, token, expectedAnswer, index, checkResults, totalChecks, coutMessage) {
        const requestData = {
            script: code,
            language: 'cpp17',
            versionIndex: '0',
            stdin: input,
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
                if (data.output && data.output.includes("error:")) {
                    checkResults.push({
                        compilationError: true,
                        actualOutput: data.output
                    });
                } else if (data.statusCode === 200) {
                    let filteredOutput = filterFinalOutput(data.output, coutMessage);
                    const normalizedOutput = cleanUpOutput(filteredOutput);

                    const passed = cleanUpOutput(expectedAnswer) === normalizedOutput;

                    checkResults.push({
                        checkIndex: index + 1,
                        passed: passed,
                        actualOutput: normalizedOutput,
                        expectedAnswer: expectedAnswer,
                        compilationError: false
                    });
                }

                if (checkResults.length === totalChecks) {
                    if (checkResults.some(result => result.compilationError)) {
                        submitIfCompilationError(checkResults.find(result => result.compilationError));
                    } else {
                        submitWithResults(checkResults);
                    }
                }
            })
    }


    function submitIfCompilationError(compilationError) {
        const compilationErrText = document.createElement('input');
        compilationErrText.type = 'hidden';
        compilationErrText.name = 'compilation_error';
        compilationErrText.value = JSON.stringify(compilationError);

        document.getElementById('createForm').appendChild(compilationErrText);
        document.getElementById('createForm').submit();
    }

    function submitWithResults(checkResults) {
        const checkResultsField = document.createElement('input');
        checkResultsField.type = 'hidden';
        checkResultsField.name = 'check_results';
        checkResultsField.value = JSON.stringify(checkResults);

        document.getElementById('createForm').appendChild(checkResultsField);
        document.getElementById('createForm').submit();
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
        coutMessage.forEach(function(message) {
            const coutRegex = new RegExp(message, 'g');
            filteredOutput = filteredOutput.replace(coutRegex, '');
        });
        return filteredOutput.trim();
    }

    function cleanUpOutput(output) {
        return output.replace(/\s+/g, ' ').trim();
    }

    let toggleThemeBtn = document.getElementById('toggleThemeBtn');

    if (toggleThemeBtn) {
        toggleThemeBtn.addEventListener('click', function() {
            let currentTheme = editor.getTheme();
            let newTheme = currentTheme === "ace/theme/dracula" ? "ace/theme/eclipse" : "ace/theme/dracula";
            editor.setTheme(newTheme);
        });
    }

});