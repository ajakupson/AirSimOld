$(document).ready(function()
{
   $('#lang').change(function()
   {
       $(this).closest('form').submit();
   })
});