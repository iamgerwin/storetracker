<script src="//cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js"></script>

<span data-bind ="text:firstName"></span>

<script type="text/javascript">
var obj = {};

obj.firstName = 'gerwin';

ko.applyBindings(obj);
</script>