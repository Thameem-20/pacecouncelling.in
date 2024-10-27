function getQueryParameter(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

function updateURLAndFetchResults(query) {
  const url = new URL(window.location);
  url.searchParams.set('q', query);
  window.history.pushState({}, '', url);

  fetch(`./index.php?q=${query}`)
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(data, 'text/html');
      document.getElementById('results').innerHTML = doc.getElementById('results').innerHTML;
    })
    .catch(error => console.error('Error:', error));
}

function handleSearchFormSubmit(event) {
  event.preventDefault();
  const query = document.getElementById('searchBar').value;
  updateURLAndFetchResults(query);
}

document.getElementById('searchForm').addEventListener('submit', handleSearchFormSubmit);

document.addEventListener('DOMContentLoaded', function() {
  const query = getQueryParameter('q') || '';
  document.getElementById('searchBar').value = query;
  if (query) {
    updateURLAndFetchResults(query);
  }
});