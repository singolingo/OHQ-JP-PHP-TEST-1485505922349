<div class="info">
  <div class="row">
    @foreach($current_data as $property)
    <div class="col-sm-6">
      <div class="property">
        <div class="property-name">
          <i class="{{ $property['icon'] }}" aria-hidden="true"></i>
          {{ $property['label'] }}
        </div>
        <div class="value">{{ $property['value'] }}</div>
        <div class="symbol">{{ $property['symbol'] }}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>
