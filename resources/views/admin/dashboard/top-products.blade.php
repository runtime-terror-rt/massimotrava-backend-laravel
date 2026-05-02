{{-- Top Products Partial --}}
{{-- Variables: $products (Collection) --}}

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Top Products</div>
      <div class="card-subtitle">By revenue this month</div>
    </div>
  </div>

  <div class="product-list">
    @php
      $gradients = [
        'linear-gradient(90deg,#6366f1,#818cf8)',
        'linear-gradient(90deg,#ec4899,#f472b6)',
        'linear-gradient(90deg,#10b981,#34d399)',
        'linear-gradient(90deg,#f59e0b,#fbbf24)',
      ];
      $maxRevenue = $products->max('revenue') ?: 1;
    @endphp

    @foreach($products as $product)
      @php
        $rank       = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
        $width      = round(($product->revenue / $maxRevenue) * 100);
        $gradient   = $gradients[$loop->index % count($gradients)];
        $formatted  = '$' . number_format($product->revenue / 1000, 1) . 'k';
      @endphp
      <div class="product-item">
        <div class="product-rank">{{ $rank }}</div>
        <div class="product-info">
          <div class="product-name">{{ $product->name }}</div>
          <div class="product-bar-wrap">
            <div class="product-bar" style="width:{{ $width }}%; background:{{ $gradient }}"></div>
          </div>
        </div>
        <div class="product-val">{{ $formatted }}</div>
      </div>
    @endforeach
  </div>
</div>
