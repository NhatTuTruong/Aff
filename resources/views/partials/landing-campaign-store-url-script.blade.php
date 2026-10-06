const campaignStoreUrl = @json($campaign->affiliate_url ?? '');
function syncGoToStoreBtn(btn) {
    if (!btn || !campaignStoreUrl) return;
    btn.setAttribute('data-url', campaignStoreUrl);
    btn.href = campaignStoreUrl;
}
