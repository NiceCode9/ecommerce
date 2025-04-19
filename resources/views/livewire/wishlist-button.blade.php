<div>
    <button class="add-to-wishlist" wire:click="toggleWishlist">
        <i class="fa fa-heart{{ $inWishlist ? '' : '-o' }}"></i>
        <span class="tooltipp">add to wishlist</span>
    </button>
</div>
