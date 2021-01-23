require(['jquery', 'Magento_Customer/js/customer-data'],function($, customerData){
	
	$("#sgform-form").on("submit",function(){
		var form = $(this)
		var url = form.attr("action");		
		$.ajax({
			url:url,
			data:form.serialize(),
			dataType: "JSON",
			showLoader: true,
			type:"post",
			success:function(data){
				$('#sgform-form')[0].reset();
			}
		})
		
		return false;
	})
})