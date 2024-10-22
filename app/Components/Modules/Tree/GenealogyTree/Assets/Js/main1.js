$(function () {
  $('.genealogy-tree ul').hide();
  $('.genealogy-tree>ul').show();
  $('.genealogy-tree ul.active').show();
  $('.genealogy-tree li').on('click', function (e) {
      var children = $(this).find('> ul');
      if (children.is(":visible")) children.hide('fast').removeClass('active');
      else children.show('fast').addClass('active');
      e.stopPropagation();
  });
});