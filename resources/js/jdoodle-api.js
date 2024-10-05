let isRunButtonClicked = false;

function displayInput(variables, coutMessage, callback) {
    const inputContainer = document.getElementById('input-container');
    const inputField = document.getElementById('input-fields');
    const submitBtn = document.getElementById('submitInputs');

    inputField.innerHTML = '';

    coutMessage.forEach((prompt) => {
        const promptElement = document.createElement('p');
        promptElement.innerText = prompt;
        inputField.appendChild(promptElement);
    });

    variables.forEach((variable) => {
        const prompt = document.createElement('p');
        prompt.innerText = `${popupi18.cout_input} ${variable}:`;
        inputField.appendChild(prompt);

        const inputDiv = document.createElement('div');
        inputDiv.innerHTML = `<input type="text" id="input_${variable}" class="form-control mb-2" placeholder="${popupi18.cout_input} ${variable}" />`;
        inputField.appendChild(inputDiv);
    });

    inputContainer.style.display = 'block';

    submitBtn.onclick = function() {
        const inputs = [];
        variables.forEach(variable => {
            const input = document.getElementById(`input_${variable}`).value;
            inputs.push(input);
        });

        inputContainer.style.display = 'none';
        callback(inputs);
    };
}


function cleanUpOutput(output) {
    return output.replace(/\s+/g, ' ').trim();
}

function replaceHardcodedValues(code, checkInputs) {
    let modifiedCode = code;

    const checkAutomatizationInput = /(int|float|double|char|string|long|unsigned|signed|(?:int|char)\s*\*)\s+(var\w*)\s*=\s*[^;]+;/g;

    let inputValues = checkInputs.split(/\s+/);

    let inputIndex = 0;

    modifiedCode = modifiedCode.replace(checkAutomatizationInput, (match, varType, varName) => {
        if (inputIndex < inputValues.length) {
            const testInput = inputValues[inputIndex++];

            if (varType === 'string' || varType === 'char') {
                return `${varType} ${varName} = "${testInput}";`;
            } else {
                return `${varType} ${varName} = ${testInput};`;
            }
        }
        return match;
    });

    return modifiedCode;
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

function clearCompilationResult() {
    const outputContainer = document.getElementById('console-output');
    outputContainer.innerHTML = '';

    const inputField = document.getElementById('input-fields');
    inputField.innerHTML = '';

    const inputContainer = document.getElementById('input-container');
    inputContainer.style.display = 'none';
}

function filterFinalOutput(finalOutput, coutMessage) {
    let filteredOutput = finalOutput;

    coutMessage.forEach(message => {
        const coutRegex = new RegExp(message, 'g');
        filteredOutput = filteredOutput.replace(coutRegex, '');
    });

    return filteredOutput.trim();
}

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
        case 'cpu-time':
            popupId = 'cpuTimePopup';
            break;
        case 'compiler-time':
            popupId = 'compilerTimePopup';
            break;
        case 'memory':
            popupId = 'memoryPopup';
            break;
        case 'submit':
            popupId = 'submitPopup';
            break;
        default:
            return;
    }

    const popup = document.getElementById(popupId);
    const popupContent = popup.querySelector('.popup-content');
    popupContent.innerText = message;
    popup.style.display = 'block';

    setTimeout(() => {
        popup.style.display = 'none';
    }, 3000);
}

document.getElementById('execTimeBtn').addEventListener('click', function() {
    const outputContainer = document.getElementById('console-output');
    const executionTime = outputContainer.getAttribute('data-exec-time');
    const message = (executionTime && executionTime !== "null") ? `${executionTime} ${popupi18.secondspopup}` : popupi18.runFirstpopup;
    showPopup('cpu-time', message);
});

document.getElementById('memoryBtn').addEventListener('click', function() {
    const outputContainer = document.getElementById('console-output');
    const memoryUsage = outputContainer.getAttribute('data-memory-usage');
    const message = (memoryUsage && memoryUsage !== "null") ? `${memoryUsage} ${popupi18.memoryUsedpopup}` : popupi18.runFirstpopup;
    showPopup('memory', message);
});

document.getElementById('compilerTimeBtn').addEventListener('click', function() {
    const outputContainer = document.getElementById('console-output');
    const compilationTime = outputContainer.getAttribute('data-compilation-time');

    const message = (compilationTime && compilationTime !== "") ?
        `~${compilationTime} ${popupi18.compilation_time}` :
        popupi18.runFirstpopup;


    showPopup('compiler-time', message);
});


document.getElementById('toggleThemeBtn').addEventListener('click', function() {
    showPopup('theme', popupi18.themepopup);
});

document.getElementById('submitBtn').addEventListener('click', async function(event) {
    event.preventDefault();

    if (!isRunButtonClicked) {
        showPopup('submit', popupi18.submitFirstpopup);
        return;
    }

    const submitIcon = document.getElementById('submitIcon');
    const submitBtn = document.getElementById('submitBtn');

    submitIcon.classList.remove('fa-upload', 'text-primary');
    submitIcon.classList.add('fa-spinner', 'fa-spin');

    submitBtn.disabled = true;

    try {
        await runAllChecks(true);
        document.getElementById('submissionForm').submit();
    } catch (error) {
        showPopup('submit', error.message);
        submitBtn.disabled = false;
    } finally {
        submitIcon.classList.remove('fa-spinner', 'fa-spin');
        submitIcon.classList.add('fa-upload', 'text-primary');
    }
});

document.getElementById('runBtn').addEventListener('click', function() {
    isRunButtonClicked = true;

    clearCompilationResult();
    showPopup('run', popupi18.runpopup);

    const code = ace.edit("editor").getValue();
    document.getElementById('code').value = code;


    const cinRegex = /(?:std::)?cin\s*>>\s*([^\s;]+(?:\s*>>\s*[^\s;]+)*)/g;
    let match;
    let variables = [];
    let containsCin = false;

    while ((match = cinRegex.exec(code)) !== null) {
        let vars = match[1].split(/\s*>>\s*/);
        variables.push(...vars);
        containsCin = true;
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
            const startTime = Date.now();

            const jDoodleAPI = '/proxy/jdoodle';
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                    const endTime = Date.now();
                    const totalTime = ((endTime - startTime) / 1000).toFixed(2);

                    const outputContainer = document.getElementById('console-output');
                    outputContainer.setAttribute('data-compilation-time', totalTime);

                    if (data.output && data.output.includes("error:")) {
                        outputContainer.innerText = `${popupi18.compilation_error}\n${data.output}`;
                        outputContainer.setAttribute('data-exec-time', "null");
                        outputContainer.setAttribute('data-memory-usage', "null");
                        return;
                    }

                    if (data.statusCode === 200) {
                        const filteredOutput = filterFinalOutput(data.output, coutMessage);
                        outputContainer.innerText = filteredOutput;
                        outputContainer.setAttribute('data-exec-time', data.cpuTime);
                        outputContainer.setAttribute('data-memory-usage', data.memory);
                        if (allowAutomaticCheckRun === true) {
                            runAllChecks(false, true);
                        }
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

        const startTime = Date.now();

        const jDoodleAPI = '/proxy/jdoodle';
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                const endTime = Date.now();
                const totalTime = ((endTime - startTime) / 1000).toFixed(2);

                const outputContainer = document.getElementById('console-output');
                outputContainer.setAttribute('data-compilation-time', totalTime);

                if (data.output && data.output.includes("error:")) {
                    outputContainer.innerText = `${popupi18.compilation_error}\n${data.output}`;
                    outputContainer.setAttribute('data-exec-time', "null");
                    outputContainer.setAttribute('data-memory-usage', "null");
                    return;
                }

                if (data.statusCode === 200 && data.isExecutionSuccess) {
                    outputContainer.innerText = data.output;
                    outputContainer.setAttribute('data-exec-time', data.cpuTime);
                    outputContainer.setAttribute('data-memory-usage', data.memory);

                    if (allowAutomaticCheckRun === true) {
                        runAllChecks(false, false);
                    }
                } else {
                    outputContainer.innerText = `${popupi18.errorpopup}: ${data.output || data.error}`;
                    outputContainer.setAttribute('data-exec-time', "null");
                    outputContainer.setAttribute('data-memory-usage', "null");
                }
            })
    }
});


function displayResults(results, correctCount) {
    const resultContainer = document.querySelector('.result-item');

    // if (!resultContainer) {
    //     console.error('tes');
    //     return;
    // }

    resultContainer.innerHTML = `
        <div class="accordion" id="resultsAccordion">
        </div>
    `;

    results.forEach((result, index) => {
        const resultItem = document.createElement('div');
        resultItem.classList.add('accordion-item');

        let inputDisplay = `<p><strong>${popupi18.test_case_input}: </strong>`;
        const inputs = result.input.split(',');

        if (inputs.length > 1) {
            inputDisplay += '<ul>';
            inputs.forEach(input => {
                inputDisplay += `<li>${input.trim()}</li>`;
            });
            inputDisplay += '</ul>';
        } else {
            inputDisplay += `${inputs[0].trim()}`;
        }
        inputDisplay += '</p>';

        resultItem.innerHTML = `
            <h2 class="accordion-header" id="heading${index}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}" aria-expanded="true" aria-controls="collapse${index}">
                    ${result.isCorrect ? '<i class="fas fa-check-circle text-success me-2"></i>' : '<i class="fas fa-times-circle text-danger me-2"></i>'} ${popupi18.test_case} ${index + 1}
                </button>
            </h2>
            <div id="collapse${index}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="heading${index}" data-bs-parent="#resultsAccordion">
                <div class="accordion-body">
                    ${inputDisplay}
                    <p><strong>${popupi18.test_case_result}: </strong> ${result.output}</p>
                    <p><strong>${popupi18.test_case_expected_answer}:</strong> ${result.expectedOutput}</p>
                    <p><strong>${popupi18.test_case_compilation_time}:</strong> ${result.compilationTime} ${popupi18.test_case_time_unit}</p>
                    <p><strong>${popupi18.test_case_cpu}:</strong> ${result.cpuTime} ${popupi18.test_case_time_unit}</p>
                    <p><strong>${popupi18.test_case_memory_use}:</strong> ${result.memoryUsage} KB</p>
                    <p><strong>${popupi18.test_case_verdict}:</strong> ${result.isCorrect ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'}</p>
                </div>
            </div>
        `;
        document.getElementById('resultsAccordion').appendChild(resultItem);
    });

    const totalResult = document.createElement('div');
    totalResult.classList.add('total-result');

    let colorClass = 'text-danger';
    if (correctCount === results.length) {
        colorClass = 'text-success';
    } else if (correctCount === results.length - 1) {
        colorClass = 'text-warning';
    }

    totalResult.innerHTML = `<p class="${colorClass}">${popupi18.total}: ${correctCount}/${results.length} ${popupi18.correct}</p>`;
    resultContainer.appendChild(totalResult);
}
async function runAllChecks(isSubmit = false, containsCin) {
    const code = ace.edit("editor").getValue();
    let results = [];
    let correctCount = 0;
    let totalCompilationTime = 0;

    let loadingIndicator;

    if (!isSubmit) {
        const resultContainer = document.getElementById('automatization-load');
        if (resultContainer) {
            resultContainer.innerHTML = '';
        }

        loadingIndicator = document.getElementById('loadingAutomatization');
        if (!loadingIndicator) {
            loadingIndicator = document.createElement('div');
            loadingIndicator.id = 'loadingAutomatization';
            loadingIndicator.className = 'loading-indicator';
            loadingIndicator.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${popupi18.automatization_load}`;
            resultContainer.appendChild(loadingIndicator);
        }

        loadingIndicator.style.display = 'block';
    }

    const coutMessage = extractCoutMessages(code);

    async function runCodeWithInput(input, index) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return new Promise((resolve, reject) => {
            const requestData = {
                script: code,
                language: 'cpp17',
                versionIndex: '0',
                stdin: input.join('\n'),
                args: '',
                hasInputFiles: false
            };

            const startTime = Date.now();
            const jDoodleAPI = '/proxy/jdoodle';

            fetch(jDoodleAPI, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        "X-Requested-With": "XMLHttpRequest",
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .then(data => {
                    const endTime = Date.now();
                    const totalTime = ((endTime - startTime) / 1000).toFixed(2);

                    totalCompilationTime += parseFloat(totalTime);

                    if (data.statusCode === 200) {
                        const filteredOutput = filterFinalOutput(data.output.trim(), coutMessage);
                        const expectedOutput = checkAnswers[index].trim();

                        const isCorrect = cleanUpOutput(filteredOutput) === cleanUpOutput(expectedOutput);
                        if (isCorrect) correctCount++;

                        results.push({
                            input: input.join(', '),
                            output: filteredOutput,
                            expectedOutput,
                            isCorrect,
                            cpuTime: data.cpuTime,
                            memoryUsage: data.memory,
                            compilationTime: totalTime
                        });
                    } else {
                        results.push({
                            input: input.join(', '),
                            output: data.output.trim(),
                            expectedOutput: checkAnswers[index].trim(),
                            isCorrect: false,
                            cpuTime: data.cpuTime,
                            memoryUsage: data.memory,
                            compilationTime: totalTime
                        });
                    }
                    resolve();
                })
                .catch(error => {
                    results.push({
                        input: input.join(', '),
                        output: error.message,
                        expectedOutput: checkAnswers[index].trim(),
                        isCorrect: false
                    });
                    resolve();
                });
        });
    }

    async function runCodeWithoutInput(input, index) {
        return new Promise((resolve, reject) => {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const modifiedCode = replaceHardcodedValues(code, input.join(' '));
            const requestData = {
                script: modifiedCode,
                language: 'cpp17',
                versionIndex: '0',
                stdin: input.join('\n'),
                args: '',
                hasInputFiles: false
            };

            const startTime = Date.now();
            const jDoodleAPI = '/proxy/jdoodle';
            fetch(jDoodleAPI, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        "X-Requested-With": "XMLHttpRequest",
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .then(data => {
                    const endTime = Date.now();
                    const totalTime = ((endTime - startTime) / 1000).toFixed(2);

                    totalCompilationTime += parseFloat(totalTime);

                    if (data.statusCode === 200) {
                        const filteredOutput = filterFinalOutput(data.output.trim(), coutMessage);
                        const expectedOutput = checkAnswers[index].trim();

                        const isCorrect = cleanUpOutput(filteredOutput) === cleanUpOutput(expectedOutput);
                        if (isCorrect) correctCount++;

                        results.push({
                            input: input.join(', '),
                            output: filteredOutput,
                            expectedOutput,
                            isCorrect,
                            cpuTime: data.cpuTime,
                            memoryUsage: data.memory,
                            compilationTime: totalTime
                        });
                    } else {
                        results.push({
                            input: input.join(', '),
                            output: data.output.trim(),
                            expectedOutput: checkAnswers[index].trim(),
                            isCorrect: false,
                            cpuTime: data.cpuTime,
                            memoryUsage: data.memory,
                            compilationTime: totalTime
                        });
                    }
                    resolve();
                })
                .catch(error => {
                    results.push({
                        input: input.join(', '),
                        output: error.message,
                        expectedOutput: checkAnswers[index].trim(),
                        isCorrect: false
                    });
                    resolve();
                });
        });
    }

    if (containsCin) {
        for (let i = 0; i < checkInputs.length; i++) {
            await runCodeWithInput(JSON.parse(checkInputs[i]), i);
        }
    } else {
        for (let i = 0; i < checkInputs.length; i++) {
            await runCodeWithoutInput(JSON.parse(checkInputs[i]), i);
        }
    }

    if (!isSubmit) {
        loadingIndicator.style.display = 'none';
    }

    saveResults(results, correctCount, isSubmit, totalCompilationTime);
}

function saveResults(results, correctCount, isSubmit = false, totalCompilationTime) {
    if (!isSubmit && allowAutomaticCheckRun === true) {
        displayResults(results, correctCount);
    }

    let totalCpuTime = 0;
    let totalMemoryUsage = 0;

    results.forEach((result) => {
        totalCpuTime += parseFloat(result.cpuTime);
        totalMemoryUsage += parseFloat(result.memoryUsage);
    });

    const avgCpuTime = totalCpuTime / results.length;
    const avgMemoryUsage = totalMemoryUsage / results.length;
    const avgCompilationTime = (totalCompilationTime / results.length).toFixed(2);

    const outputContainer = document.getElementById('console-output');
    outputContainer.setAttribute('data-compilation-time', avgCompilationTime);

    if (isNaN(avgCpuTime) || isNaN(avgMemoryUsage)) {
        throw new Error(popupi18.compilation_submit_error);
    }

    document.getElementById('cpu_time').value = avgCpuTime;
    document.getElementById('memory_time').value = avgMemoryUsage;
    document.getElementById('compilation_time').value = avgCompilationTime;
    document.getElementById('test_result_1').value = results[0] ? results[0].output : '';
    document.getElementById('test_result_2').value = results[1] ? results[1].output : '';
    document.getElementById('test_result_3').value = results[2] ? results[2].output : '';
    document.getElementById('auto_check_correct_cases').value = `${correctCount}/${results.length}`;
}