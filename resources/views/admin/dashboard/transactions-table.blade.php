{{-- Transactions Table Partial --}}
{{-- Variables: $transactions (LengthAwarePaginator) --}}

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Recent Transactions</div>
      <div class="card-subtitle">Showing {{ $transactions->count() }} of {{ $transactions->total() }} entries</div>
    </div>
    <div class="card-actions">
      <div class="tab-group">
        <button class="tab-btn {{ request('status', 'all') === 'all'     ? 'active' : '' }}"
                onclick="filterTransactions('all')">All</button>
        <button class="tab-btn {{ request('status') === 'active'         ? 'active' : '' }}"
                onclick="filterTransactions('active')">Paid</button>
        <button class="tab-btn {{ request('status') === 'pending'        ? 'active' : '' }}"
                onclick="filterTransactions('pending')">Pending</button>
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
        @forelse($transactions as $transaction)
          @php
            $colors = ['#6366f1','#ec4899','#10b981','#f59e0b','#3b82f6','#8b5cf6','#ef4444','#06b6d4'];
            $color  = $colors[$loop->index % count($colors)];
            $initials = strtoupper(substr($transaction->user->name ?? 'NA', 0, 1) .
                        substr(strrchr($transaction->user->name ?? ' A', ' '), 1, 1));
          @endphp
          <tr>
            <td>
              <div class="td-user">
                <div class="td-avatar" style="background:{{ $color }}">{{ $initials }}</div>
                <div>
                  <div class="td-name">{{ $transaction->user->name ?? '—' }}</div>
                  <div class="td-email">{{ $transaction->user->email ?? '—' }}</div>
                </div>
              </div>
            </td>
            <td style="color:var(--text-muted)">
              {{ $transaction->created_at->format('M d, Y') }}
            </td>
            <td>
              <span class="td-amount">${{ number_format($transaction->amount, 0) }}</span>
            </td>
            <td>
              @php
                $badgeMap = [
                  'active'   => 'badge-active',
                  'pending'  => 'badge-pending',
                  'inactive' => 'badge-inactive',
                ];
                $badgeClass = $badgeMap[$transaction->status] ?? 'badge-inactive';
              @endphp
              <span class="badge {{ $badgeClass }}">
                <span class="badge-dot"></span>
                {{ ucfirst($transaction->status) }}
              </span>
            </td>
            <td>
              <div class="action-btns">
                <a href="{{ route('admin.transactions.edit', $transaction) }}"
                   class="action-btn edit" title="Edit">
                  <i class="fa-solid fa-pen"></i>
                </a>
                <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}"
                      onsubmit="return confirm('Delete this transaction?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="action-btn delete" title="Delete">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center; color:var(--text-muted); padding:32px;">
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
      Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}
      of {{ $transactions->total() }}
    </span>
    <div class="page-btns">
      {{-- Previous --}}
      @if($transactions->onFirstPage())
        <button class="page-btn" disabled>
          <i class="fa-solid fa-chevron-left" style="font-size:10px"></i>
        </button>
      @else
        <a href="{{ $transactions->previousPageUrl() }}" class="page-btn">
          <i class="fa-solid fa-chevron-left" style="font-size:10px"></i>
        </a>
      @endif

      {{-- Page Numbers --}}
      @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
        @if($page === 1 || $page === $transactions->lastPage() || abs($page - $transactions->currentPage()) <= 1)
          <a href="{{ $url }}"
             class="page-btn {{ $page === $transactions->currentPage() ? 'active' : '' }}">
            {{ $page }}
          </a>
        @elseif(abs($page - $transactions->currentPage()) === 2)
          <button class="page-btn" disabled style="opacity:.4">…</button>
        @endif
      @endforeach

      {{-- Next --}}
      @if($transactions->hasMorePages())
        <a href="{{ $transactions->nextPageUrl() }}" class="page-btn">
          <i class="fa-solid fa-chevron-right" style="font-size:10px"></i>
        </a>
      @else
        <button class="page-btn" disabled>
          <i class="fa-solid fa-chevron-right" style="font-size:10px"></i>
        </button>
      @endif
    </div>
  </div>

</div>{{-- end card --}}
