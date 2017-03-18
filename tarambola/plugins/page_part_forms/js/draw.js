var offset_data; //Global variable as Chrome doesn't allow access to event.dataTransfer in dragover
var currentId = 0;
//var points = new Array();
if(gr==null)
{
    var gr = new jsGraphics(document.getElementById("canvasDraw"));
}
jQuery("#canvasDraw").click(function(ev) 
            {      
                var offsetY = jQuery("#canvasDraw").offset().top;
                var offsetX = jQuery("#canvasDraw").offset().left;
                mouseX = ev.pageX;
                mouseY = ev.pageY;
                points.push({x: Math.round(mouseX-offsetX), y: Math.round(mouseY-offsetY)});
                var color = '#ffccc99';
                var size = '1px';
                jQuery("#canvas2").append(
                        jQuery('<aside class="point dragme" id="' + points.length + '" draggable="true"><span class="pointId">' + points.length + '</span></aside>')
                        .css('position', 'absolute')
                        .css('top', mouseY - offsetY - 10 + 'px')
                        .css('left', mouseX - offsetX - 10 + 'px')
                        .css('width', size)
                        .css('height', size)
                        .css('background-color', color)
                        .css('border-radius', 10 + 'px')
                        );
                console.log(points);
                addgrag();  
                ev.stopPropagation();
            });
            
            function setDrawEvent(id)
            {
                jQuery("#drawBtn").click(function(ev1) {
                     var offsetY = jQuery("#canvasDraw").offset().top;
                     var offsetX = jQuery("#canvasDraw").offset().left;
                     ev1.stopPropagation();
                     var col = new jsColor("silver");
                     var pen = new jsPen(col, 3);
                     var pointsF=new Array();
                     gr.clear();
                     var json = "{'Polygon':[";
                     for(i=0; i<points.length; i++)
                     {
                         pointsF.push({x:points[i].x, y:points[i].y});
                         if(i!=0)
                             json+=",";
                         json+="{'x':"+points[i].x+", 'y':"+points[i].y+"}";
                     }
                     json+="]}";
                     gr.fillPolygon(col, pointsF);
                     //obj = JSON.parse(json);
                     jQuery("#"+id).val(json);
                     return(false);
                 });
                 jQuery("#clearBtn").click(function(event){
                     gr.clear();
                     points = new Array();
                     jQuery(".point").remove();
                     var json = "{'Polygon':[]}";
                     jQuery("#"+id).val(json);
                     return(false);
                 });
            }
            function drawContent(json)
            {
                if(json!="")
                {
                    var dm = jQuery(".dragme");
                    var l = dm.length;
                    var offsetY = jQuery("#canvasDraw").offset().top;
                    var offsetX = jQuery("#canvasDraw").offset().left;
                    if(l==0)
                    {
                        json = json.replace(/'/g, "\"");
                        var obj = JSON.parse(json);
                        var pointsF = obj.Polygon;
                        var color = '#ffccc99';
                        var size = '1px';
                        for(var i=0; i<pointsF.length; i++)
                        {
                            points.push({x:pointsF[i].x, y:pointsF[i].y});
                            jQuery("#canvas2").append(
                                jQuery('<aside class="point dragme" id="' + (i+1) + '" draggable="true"><span class="pointId">' + (i+1) + '</span></aside>')
                                .css('position', 'absolute')
                                .css('top', pointsF[i].y  - 10 + 'px')
                                .css('left', pointsF[i].x - 10 + 'px')
                                .css('width', size)
                                .css('height', size)
                                .css('background-color', color)
                                .css('border-radius', 10 + 'px')
                                );
                        }
                        var col = new jsColor("silver");
                        var pen = new jsPen(col, 3);
                        gr.clear();
                        gr.fillPolygon(col, pointsF);
                        addgrag(); 
                   }
                }
            }

            function drag_start(event) {
                var style = window.getComputedStyle(event.target, null);
                currentId = event.target.id;
                offset_data = (parseInt(style.getPropertyValue("left"), 10) - event.clientX) + ',' + (parseInt(style.getPropertyValue("top"), 10) - event.clientY);
                event.dataTransfer.setData("text/plain", offset_data);
                 //event.stopPropagation();
            }
            function drag_over(event) {
                var offset;
                try {
                    offset = event.dataTransfer.getData("text/plain").split(',');
                }
                catch (e) {
                    offset = offset_data.split(',');
                }
                var dm = jQuery(".dragme");
                dm[currentId - 1].style.left = (event.clientX + parseInt(offset[0], 10)) + 'px';
                dm[currentId - 1].style.top = (event.clientY + parseInt(offset[1], 10)) + 'px';
                //event.stopPropagation();
                event.preventDefault();
                return false;
            }
            function drop(event) {
                var offset;
                try {
                    offset = event.dataTransfer.getData("text/plain").split(',');
                }
                catch (e) {
                    offset = offset_data.split(',');
                }
                var dm = jQuery(".dragme");
                dm[currentId - 1].style.left = (event.clientX + parseInt(offset[0], 10))  + 'px';
                dm[currentId - 1].style.top = (event.clientY + parseInt(offset[1], 10))  + 'px';
                points[currentId - 1].x = dm[currentId - 1].style.left = (event.clientX +10 + parseInt(offset[0], 10));
                points[currentId - 1].y = dm[currentId - 1].style.top = (event.clientY +10 + parseInt(offset[1], 10));
                //event.stopPropagation();
                event.preventDefault();
                return false;
            }
            function addgrag() {

                var dm = jQuery(".dragme");
                var l = dm.length;
                for (i = 0; i < l; i++)
                {
                    dm[i].addEventListener('dragstart', drag_start, false);
                }

                document.body.addEventListener('dragover', drag_over, false);
                document.body.addEventListener('drop', drop, false);
            }