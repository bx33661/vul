// Show Percent Value
function refreshPercent(value) {
		var raid1Percent = value;
		var linearPercent = 100 - raid1Percent;
		
		jQuery("#raid1Slider").html("RAID 1 : <span id='raid1Percent'>"+raid1Percent+"</span>%");
		jQuery("#linearSlider").html("Linear : <span id='linearPercent'>"+linearPercent+"</span>%");
	}

function showSlider(){
	var raidValue = jQuery("#idSelect3").val();
	if(raidValue == "raidlinear"){
		jQuery("#sliderContainer").show();
	}
	else{
		jQuery("#sliderContainer").hide();
	}
}	

jQuery(document).ready(function(){
		jQuery("#raid1_linear").slider({
			orientation : 'horizontal',
			range:"min",
			min:0,
			max:100,
			value:50,
			step:5,
			slide:function(event, ui) {
				refreshPercent(ui.value);
			}

		

		});
		
		refreshPercent(50);
		jQuery("#sliderContainer").hide();
}) 