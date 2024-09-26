/**
 * @type {string}
 */
const selectAllSelector = '#js-loan-current-select-all';

/**
 * @type {string}
 */
const resetSelector = '#js-loan-current-reset';

/**
 * @type {string}
 */
const renewSelector = '#js-loan-current-renew';

/**
 * @type {string}
 */
const itemSelector = '.js-loan-current-item';

/**
 * @type {string}
 */
const modalCheckboxSelector = '#renewLoanConfirm';

/**
 * @type {HTMLObjectElement|Element}
 */
const selectAllBtn = document.querySelector(selectAllSelector);

/**
 * @type {HTMLObjectElement|Element}
 */
const resetBtn = document.querySelector(resetSelector);

/**
 * @type {HTMLObjectElement|Element}
 */
const submitBtn = document.querySelector(renewSelector);

/**
 * @type {NodeListOf<Element>}
 */
const itemElements = document.querySelectorAll(itemSelector);

/**
 * @type {HTMLObjectElement|Element}
 */
const modalCheckboxElement = document.querySelector(modalCheckboxSelector);

/**
 * @type {string}
 */
const submitText = submitBtn.innerHTML;


export const listenCheckItems = () => {
  itemElements.forEach((itemElement) => {
    itemElement.addEventListener('change', () => toggleCheckbox(itemElement));
  });
}

const toggleCheckbox = (itemElement) => {

  if (localStorage.length > 0) {
    let renewalChecked = localStorage.getItem('slub-renewal');

    if (renewalChecked == null) {
      // Open Modal
      let alertModal = modalCheckboxElement;

      // Transmit due date of the data record to Modal
      loanDueDate(itemElement);

      // Listen to Choose button in the modal
      let modalChooseBtn = alertModal.querySelector('.js-loan-current-modal-choose');
      modalChooseBtn.onclick = function () {
        itemElement.checked = true;
        countingCheckboxes();
      };

      alertModal.addEventListener('hide.bs.modal',
        this,
        // console.log('checkboxElement:', itemElement),
        // Selected/deselected checkboxes
        itemElement.checked = false
      );
    } else {
      // Initially the checkboxes are not checked
      if (itemElement.checked) {
        // Activate checkbox
        itemElement.checked = true;
      } else {
        itemElement.checked = false;
      }
    }
  }

  // Counting the checkboxes for the behaviour of the send button
  countingCheckboxes();
}

// Due date in the modal
const loanDueDate = (itemElement) => {
  if (itemElement) {
    let dueDays = itemElement.getAttribute('data-days-to-due');
    modalCheckboxElement.getElementsByClassName('days-to-due')[0].innerHTML = dueDays;
  }
}

// Count loans to be renewed 
const countingCheckboxes = () => {
  let count = 0;

  for (var i = 0; i < itemElements.length; i++) {
    if (itemElements[i].type === 'checkbox' && itemElements[i].checked === true) {
      count++;
    }
  }

  // Show select-all Button, if not equal to count
  if (i === count) {
    selectAllBtn.disabled = true;
  } else {
    selectAllBtn.disabled = false;
  }

  if (count > 0) {
    submitBtn.removeAttribute('disabled');
    submitBtn.textContent = count + submitText;
    resetBtn.disabled = false;
  } else {
    submitBtn.setAttribute('disabled', '');
    submitBtn.textContent = submitText + '';
    resetBtn.disabled = true;
  }
}