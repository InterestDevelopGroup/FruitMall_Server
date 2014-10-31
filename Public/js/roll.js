(function($) {
function $(id){return typeof id=="string"?document.getElementById(id):id;}
	function $$(id,tagname){return typeof id=="string"?document.getElementById(id).getElementsByTagName(tagname):id.getElementsByTagName(tagname);}
	function AddTab(idNav,idCon,idLeft,idRight,idNavSun,idConSun){
		this.idNav=$(idNav);
		this.idCon=$(idCon);
		this.idLeft=$(idLeft);
		this.idRight=$(idRight);
		this.navLiList=$$(idNav,idNavSun);
		this.conLiList=$$(idCon,idConSun);
		this.navLength=$$(idNav,idNavSun).length;
		this.conLength=$$(idNav,idConSun).length;
	}
	AddTab.prototype={
		constructor : AddTab,
		addHd : function(oT,sT,fN){
			if (oT.addEventListener){
				oT.addEventListener(sT,fN,false);
			}
			else {
				oT["on"+sT]=fN;
			}
		},
		classTab : function(e,nowFocus,leftOrR){
			if (typeof nowFocus=="number")
			{
				if (leftOrR=="left")
				{
					for (i=0;i<this.navLength ;i++ )
						{
							if (nowFocus==0){nowFocus=4;}
							this.navLiList[i].className=i==nowFocus-1?"hover":"";
							this.conLiList[i].className=i==nowFocus-1?"show":"";
						}
				}
				else if (leftOrR=="right"){
					for (i=0;i<this.navLength ;i++ )
						{
							if (nowFocus==this.navLength-1){nowFocus=-1;}
							this.navLiList[i].className=i==nowFocus+1?"hover":"";
							this.conLiList[i].className=i==nowFocus+1?"show":"";
						}
				}
			}
			else {
				for (i=0;i<this.navLength ;i++ )
				{
					this.navLiList[i].className=this.navLiList[i]==e?"hover":"";
					this.conLiList[i].className=this.navLiList[i]==e?"show":"";
				}
			}
		},
		addHdEach : function(){
			var oTa=this;
			for (i=0;i<this.navLength ;i++ )
			{
				oTa.addHd(oTa.navLiList[i],"mouseover",function(){oTa.classTab(this);})
			}
			oTa.addHd(oTa.idLeft,"click",function(){
				var nowFocus=oTa.nowFocus();
				oTa.classTab("",nowFocus,"left")
			});
			oTa.addHd(oTa.idRight,"click",function(){
				var nowFocus=oTa.nowFocus();
				oTa.classTab("",nowFocus,"right")
			});
			oTa.addHd(oTa.idLeft,"mousedown",function(){
				this.className="showp";
			});
			oTa.addHd(oTa.idLeft,"mouseup",function(){
				this.className="";
			});
			oTa.addHd(oTa.idRight,"mousedown",function(){
				this.className="showp";
			});
			oTa.addHd(oTa.idRight,"mouseup",function(){
				this.className="";
			});
			oTa.idNav.onselectstart=function(){return false;};
			oTa.idLeft.onselectstart=function(){return false;};
			oTa.idRight.onselectstart=function(){return false;};
		},
		nowFocus : function(){
			for (i=0;i<this.navLength ;i++ )
			{
				if (this.navLiList[i].className=="hover") return i;
			}
		}
	}
	window.onload=function(){
	var person1 = new AddTab("small_pic","big_pic","left2","right2","li","li");
	person1.addHdEach();
	}
})(jQuery)