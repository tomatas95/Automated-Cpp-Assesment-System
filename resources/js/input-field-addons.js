document.addEventListener('DOMContentLoaded', function() {
    function additionalInputAddon(automatizationCheckNumber, value = '', initialInput = false) {
        const additionalInputParent = document.getElementById(automatizationCheckNumber);

        const newInputDiv = document.createElement('div');
        newInputDiv.classList.add('input-group', initialInput ? 'mt-0' : 'mt-2');
        newInputDiv.innerHTML = `
            <input type="text" class="form-control" name="${automatizationCheckNumber.replace('Inputs', '')}[]" placeholder="${jsvalidi18.add_new_input}" value="${value !== null ? value : ''}" >
            <span class="input-group-text check-ans-icon-bg"><i class="fa-solid fa-question fa-lg"></i></span>
        `;
        additionalInputParent.appendChild(newInputDiv);
        inputAction(automatizationCheckNumber);
    }

    function inputAction(automatizationCheckNumber) {
        const additionalInputParent = document.getElementById(automatizationCheckNumber);
        const inputNumber = additionalInputParent.querySelectorAll('.input-group');
        const removeInput = additionalInputParent.parentElement.querySelector('.btn.text-danger');

        if (inputNumber.length > 1) {
            removeInput.style.display = 'inline';
        } else {
            removeInput.style.display = 'none';
        }
    }

    document.querySelectorAll('.add-input-btn').forEach(button => {
        button.addEventListener('click', function() {
            const automatizationCheckNumber = this.getAttribute('data-target');
            if (this.classList.contains('text-danger')) {
                const additionalInputParent = document.getElementById(automatizationCheckNumber);
                if (additionalInputParent.children.length > 1) {
                    additionalInputParent.removeChild(additionalInputParent.lastChild);
                    inputAction(automatizationCheckNumber);
                }
            } else {
                additionalInputAddon(automatizationCheckNumber);
            }
        });
    });

    function printCheckArray() {
        const checks = ['check1', 'check2', 'check3'];

        checks.forEach(check => {
            const automatizationCheckNumber = `${check}Inputs`;
            const additionalInputParent = document.getElementById(automatizationCheckNumber);
            const inputValues = JSON.parse(additionalInputParent.getAttribute('data-input-vals') || '[]');

            additionalInputParent.innerHTML = '';

            if (inputValues.length === 0) {
                additionalInputAddon(automatizationCheckNumber, '', true);
            } else {
                inputValues.forEach((value, index) => {
                    additionalInputAddon(automatizationCheckNumber, value, index === 0);
                });
            }

            inputAction(automatizationCheckNumber);
        });
    }

    printCheckArray();
});