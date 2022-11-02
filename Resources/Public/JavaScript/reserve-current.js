/**
 * @type {string}
 */
const listSelector = '#js-reserve-current-action';

/**
 * @type {Element}
 */
const listElement = document.querySelector(listSelector);

if (listElement) {
  let listSelectAll = await import('./Service/ReserveCurrent/select-all.js');

  listSelectAll.listenButton();
}
