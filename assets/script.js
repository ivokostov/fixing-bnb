jQuery(document).ready(function () {
  jQuery(".sortable-bnb").sortable({
    update: function( event, ui ) { jQuery('.js-trigger-sortable').trigger('change'); }
  });
  jQuery(".sortable-bnb").disableSelection();
  console.log(234234234234);
});
jQuery(document).on('widget-updated', function(event, widget) {
  console.log(widget);
  jQuery(widget).find(".sortable-bnb").sortable({
    update: function( event, ui ) { jQuery('.js-trigger-sortable').trigger('change'); }
  });
   jQuery(widget).find('.sortable-bnb').disableSelection();
});
