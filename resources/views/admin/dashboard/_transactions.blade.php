{{-- ===== TRANSACTIONS TABLE ===== --}}
<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Recent Transactions</div>
      <div class="card-subtitle">Showing {{ $transactions->count() }} of {{ $transactions->total() }} entries</div>
    </div>
    <div class="card-actions">
      <div class="tab-group">
        <button class="tab-btn {{ request('status', 'all') === 'all'     ? 'active' : '' }}"
                onclick="filterTable('all')">All</button>
        <button class="tab-btn {{ request('status') === 'active'         ? 'active' : '' }}"
                onclick="filterTable('active')">Paid</button>
        <button class="tab-btn {{ request('status') === 'pending'        ? 'active' : '' }}"
                onclick="filterTable('pending')">Pending</button>
      </div>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Customer</th>
          <th>Date</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $row)
        <tr>
          <td>
            <div class="td-user">
              <div class="td-avatar" style="background:{{ $row['avatar_color'] }}">
                {{ strtoupper(substr($row['name'], 0, 1) . substr(strstr($row['name'], ' '), 1, 1)) }}
              </div>
              <div>
                <div class="td-name">{{ $row['name'] }}</div>
                <div class="td-email">{{ $row['email'] }}</div>
              </div>
            </div>
          </td>
          <td style="color:var(--text-muted)">{{ $row['date'] }}</td>
          <td><span class="td-amount">{{ $row['amount'] }}</span></td>
          <td>
            <span class="badge badge-{{ $row['status'] }}">
              <span class="badge-dot"></span>
              {{ ucfirst($row['status']) }}
            </span>
          </td>
          <td>
            <div class="action-btns">
              <button class="action-btn edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
              <button class="action-btn delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center; padding:32px; color:var(--text-muted)">
            No transactions found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="pagination">
    <span>
      Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }}
    </span>
    <div class="page-btns">
      {{ $transactions->links('components.pagination') }}
    </div>
  </div>
</div>
