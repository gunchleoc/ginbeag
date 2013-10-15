$(document).ready(function() {

	// movetop
	$("#{JSID}movetop").click(function() {
		self.location.href=projectroot+'admin/pageedit.php?page='+$("#{JSID}page").val()+'&positions='+$("#{JSID}positions").val()+'&movetop=movetop&action=move'
	}); // movetop
		

	// moveup
	$("#{JSID}moveup").click(function() {
		self.location.href=projectroot+'admin/pageedit.php?page='+$("#{JSID}page").val()+'&positions='+$("#{JSID}positions").val()+'&moveup=moveup&action=move'
	}); // moveup
		

	// movedown
	$("#{JSID}movedown").click(function() {
		self.location.href=projectroot+'admin/pageedit.php?page='+$("#{JSID}page").val()+'&positions='+$("#{JSID}positions").val()+'&movedown=movedown&action=move'
	}); // movedown

	// movebottom
	$("#{JSID}movebottom").click(function() {
		self.location.href=projectroot+'admin/pageedit.php?page='+$("#{JSID}page").val()+'&positions='+$("#{JSID}positions").val()+'&movebottom=movebottom&action=move'
	}); // movebottom

}); // document