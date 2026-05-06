@if($message = session()->get('success'))
<script type="text/javascript">
      success("{{ $message}}");
</script>
@endif

@if($message = session()->get('error'))
<script type="text/javascript">
      error("{{ $message }}");
</script>
@endif

@if($message = session()->get('warning'))
<script type="text/javascript">
      warning("{{ $message }}");
</script>
@endif

@if($message = session()->get('info'))
<script type="text/javascript">
      info("{{ $message }}");
</script>
@endif

@isset($errors)
@if($message = $errors->first())
<script  type="text/javascript">
      error("{{ $message }}");
</script>
@endif
@endisset
