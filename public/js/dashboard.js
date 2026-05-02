// ===== TRANSACTION TAB FILTER =====
// Reloads the page with a ?status= query param when a tab is clicked.

function filterTransactions(status) {
  const url = new URL(window.location.href);
  if (status === 'all') {
    url.searchParams.delete('status');
  } else {
    url.searchParams.set('status', status);
  }
  url.searchParams.delete('page'); // reset to page 1
  window.location.href = url.toString();
}
