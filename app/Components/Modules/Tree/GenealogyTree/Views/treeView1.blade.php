<section class="management-hierarchy">
    <div class="genealogy-body genealogy-scroll">
        <div class="genealogy-tree">
            {!! $downLines !!}
        </div>
    </div>
</section>

<script>
    "use strict";

    //refresh
    function refreshTree(userId, appendTo) {
        if (!appendTo) $('.hv-item img').attr('src', '{!! asset("reload.gif") !!}');

        return $.get('{!! route(strtolower(getScope()).".tree.genealogyTree1.reload") !!}/' + userId, {
            markupOnly: true,
            type: '{{ $type }}'
        }, function (response) {
            if (appendTo) {
                appendTo.html(response);
                return;
            }
            $('.genealogy-tree').html(response);

            $('.genealogy-tree ul').hide();
            $('.genealogy-tree>ul').show();
            $('.genealogy-tree ul.active').show();
            $('.genealogy-tree li a').on('click', function (e) {
              var children = $(this).parent().find('> ul');
              if (children.is(":visible")) children.hide('fast').removeClass('active');
              else children.show('fast').addClass('active');
              e.stopPropagation();
            });

            $('.genealogy-tree img').each(function () {
                var me = $(this);
                var userId = me.attr('data-id');
                $(this).webuiPopover({
                    trigger: 'hover',
                    type: 'async',
                    url: "{{ scopeRoute('tree.genealogyTree1.tooltip',['id'=> false]) }}/" + userId,
                });
            });
        });
    }

    //Document ready functions
    $(document).ready(function () {
        $('body').addClass('page-sidebar-closed');
        $('.page-sidebar-menu').addClass('page-sidebar-menu-closed');

        if ('{{ $moduleData->get("view_tooltip") }}' == 1) {
            $('.genealogy-tree img').each(function () {
                var me = $(this);
                var userId = me.attr('data-id');
                $(this).webuiPopover({
                    trigger: 'hover',
                    type: 'async',
                    url: "{{ scopeRoute('tree.genealogyTree1.tooltip',['id'=> false]) }}/" + userId,
                });
            });
        }

        $('body').on('keyup', '.userFilter input[type="text"]', function () {
            console.log($(this).val())
            var me = this;
            var options = {
                keyword: $(this).val()
            };
            getUsers(options).done(function (response) {
                $('.ajaxDropDown .innerWrap').empty();
                if (response.length) {
                    response.forEach(function (value, key) {
                        $('.ajaxDropDown .innerWrap').append('<div class="eachResult user" data-id="' + value.id + '">' + value.username + '</div>');
                    });
                } else {
                    $('.ajaxDropDown .innerWrap').html('<div class="ajaxNoResult"><i class="fa fa-meh-o"></i> No result !</div>');
                }

                adjustPosition($('.ajaxDropDown'), $(me));
            });
        });

        $(function () {
            //User fetcher
            var selectedCallback = function (target, id, name) {
                $('.userFiller').val(name);
                refreshTree(id)

            };

            var options = {
                target: '.userFiller',
                route: '{{ route("user.api") }}',
                action: 'getUsers',
                name: 'username',
                ajaxData: {downlineRelation: '{{ $type }}', includeSelf: true},
                selectedCallback: selectedCallback
            };
            $('.userFiller').ajaxFetch(options);
        });
    });

    $(function () {
      $('.genealogy-tree ul').hide();
      $('.genealogy-tree>ul').show();
      $('.genealogy-tree ul.active').show();
      $('.genealogy-tree li a').on('click', function (e) {
          var children = $(this).parent().find('> ul');
          if (children.is(":visible")) children.hide('fast').removeClass('active');
          else children.show('fast').addClass('active');
          e.stopPropagation();
      });
    });
</script>
