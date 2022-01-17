import * as messageStatus from '../Message/status.js'
import * as searchQueryRequest from './request.js'

/**
 * @type {string}
 */
const addSelector = '#js-search-query-add';

/**
 * @type {string}
 */
const addButtonSelector = '#js-search-query-add-button';

/**
 * @type {string}
 */
const searchTypeSelector = '#search-modifier-group .search-modifier.active';

/**
 * @type {HTMLObjectElement|Element}
 */
const addElement = document.querySelector(addSelector);

/**
 * @type {HTMLObjectElement|Element}
 */
const addButtonElement = document.querySelector(addButtonSelector);

/**
 * @type {({identifier: string, type: string}|{identifier: string, type: string})[]}
 */
const searchTypes = [
  {
    type: 'simple',
    identifier: 'catalog'
  },
  {
    type: 'extended',
    identifier: 'extendedsearch'
  }
];

/**
 * @returns {string}
 */
export const showAddButton = () => addElement.style.display = 'block';

export const listenAddButton = () => addButtonElement.addEventListener('click', () => addSearchQuery());

const addSearchQuery = async () => {
  const searchType = getSearchType();
  const numberOfResults = getNumberOfResults() ?? 0;
  const query = await getQuery(searchType) ?? [];
  const searchQuery = {
    type: searchType,
    numberOfResults: numberOfResults,
    query: query,
  };

  if (query.length === 0) {
    messageStatus.initialize(501);
  } else {
    searchQueryRequest.addSearchQuery(searchQuery)
      .then(data => messageStatus.initialize(parseInt(data.code)))
      .catch(error => console.error(error));
  }
}

/**
 * @returns {number}
 */
const getNumberOfResults = () => parseInt(addButtonElement.dataset.resultCount);

/**
 * @param {string} searchType
 * @returns []
 */
const getQuery = async (searchType) => {
  const search = await import(`./Types/${searchType}-search.js`)

  return search.getQuery();
}

/**
 * @returns {string|undefined}
 */
const getSearchType = () => {
  const searchTypeElement = document.querySelector(searchTypeSelector);
  let currentSearchType = {};

  searchTypes.forEach((searchType) => {
    if (searchTypeElement.classList.contains(searchType.identifier)) {
      currentSearchType = searchType;
    }
  });

  return currentSearchType.type;
}