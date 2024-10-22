<!-- END PAGE CONTAINER -->
</div>
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        {!! getConfig('company_information','company_name') !!} &copy; @php echo date('Y') @endphp
    </div>
    <div class="page-footer-tools">
        <span class="go-top">
        <i class="fa fa-angle-up"></i>
        </span>
    </div>
</div>
<!-- END FOOTER -->

<!-- END THEME SCRIPTS -->
@include('Admin.Layout.js')
@stack('scripts')
<!-- END THEME SCRIPTS -->

</div>
</body>
</html>