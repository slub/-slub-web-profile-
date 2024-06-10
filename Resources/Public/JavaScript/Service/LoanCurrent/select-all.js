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
const itemCheckedSelector = '.js-loan-current-item:checked';

/**
 * @type {string}
 */
const modalCheckboxSelector = '#renewLoanConfirmAll';

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


export const listenButton = () => {
  selectAllBtn.addEventListener('click', () => selectAllItems());
  resetBtn.addEventListener('click', () => resetAllItems());
}

const selectAllItems = () => {
  // Open Modal
  let alertModal = modalCheckboxElement;

  let modalChooseBtn = alertModal.querySelector('.js-loan-current-modal-choose');
  modalChooseBtn.onclick = function () {

    itemElements.forEach((item) => {
      item.setAttribute('checked', 'checked');
      item.checked = true;

      btnStatus('inactive');
      countingCheckboxes();
    });
  };
}

// Reset all checkboxes
const resetAllItems = () => {
  itemElements.forEach((item) => {
    item.removeAttribute('checked');
    item.checked = false;

    btnStatus('active');
    countingCheckboxes();
  });
}

/**
 * @param type
 */
const btnStatus = (type) => {
  if (type === 'inactive') {
    selectAllBtn.disabled = true;
    resetBtn.disabled = false;
  } else {
    selectAllBtn.disabled = false;
    resetBtn.disabled = true;
  }
}

/**
 * @returns {[]}
 */
export const selectedIds = () => {
  // Has to be set here to react dynamically
  const items = document.querySelectorAll(itemCheckedSelector);
  let selectedIds = [];

  items.forEach((item) => selectedIds.push(parseInt(item.value)));

  return selectedIds;
}

const countingCheckboxes = () => {
  let count = 0;

  for (var i = 0; i < itemElements.length; i++) {
    if (itemElements[i].type === 'checkbox' && itemElements[i].checked === true) {
      count++;
    }
  }

  if (count > 0) {
    submitBtn.removeAttribute('disabled');
    submitBtn.textContent = count + submitText;
    resetBtn.disabled = false;
  } else {
    submitBtn.setAttribute('disabled', '');
    submitBtn.textContent = submitText + '';
  }
}