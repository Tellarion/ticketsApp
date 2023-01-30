@extends('default')

@section('content')
<div style="position: relative;">
    <div class="canvas-holder" style="margin-top: 150px; margin-bottom: 50px; width: 100%; height: 100vh;">
        <canvas id="canvas1" style="border: 3px dashed #161b22;">
            Ваш браузер не поддерживает элемент canvas.
        </canvas>
    </div>
    <div class="actions">
        <input type="text" id="username" class="form-control" placeholder="Enter username">
        <button type="button" id="addBooking" class="btn btn-outline-success">Booking</button>
    </div>
    <div id="canvas1_map_container">
    <canvas id="canvas1_map">
        Ваш браузер не поддерживает элемент canvas.
    </canvas>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Progress</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="{{ env('APP_URL') }}/js/jquery.min.js"></script>
<script>

    var selectEventShow = {!! $booking->id !!};

    var places = {!! $booking->places !!};

    function writeModalError(text) {
        $('.modal-body').html(`<div class="alert alert-danger" role="alert">${text}</div>`)
    }

    function writeModalSuccess(text) {
        $('.modal-body').html(`<div class="alert alert-success" role="alert">${text}</div>`)
    }

    $('#addBooking').on('click', function() {
        $.ajax({
            url: '/api/addBooking',
            method: 'POST',
            dataType: 'json',
            data: {eventId: selectEventShow, username: $('#username').val(), seats: JSON.stringify(placesSelected)},
            success: function(data){
                if(data.status == true) {
                    writeModalSuccess(data.message)
                    // window.location.reload()
                } else {
                    writeModalError(data.message)
                }
                $('#exampleModal').modal('show')
            }
        })
    })

    var placesSelected = []

    var backgroundLayer = new SchemeDesigner.Layer('background', {zIndex: 0, visible: false, active: false});
    var defaultLayer = new SchemeDesigner.Layer('default', {zIndex: 10});

    var renderPlace = function (schemeObject, schemeDesigner, view) {

        var context = view.getContext();

        var backgroundColor = '#' + schemeObject.params.backgroundColor;

        context.beginPath();
        context.lineWidth = 4;
        context.strokeStyle = 'white';

        var isHovered = schemeObject.isHovered && !SchemeDesigner.Tools.touchSupported();

        context.fillStyle = '#fff';
		
        if (schemeObject.params.isSelected && isHovered) {
            context.strokeStyle = 'orange';
        } else if (isHovered) {
            context.fillStyle = 'white';
            context.strokeStyle = 'orange';
        } else if (schemeObject.params.isSelected) {
            context.fillStyle = 'white';
            context.strokeStyle = 'orange';
        }

        var relativeX = schemeObject.x;
        var relativeY = schemeObject.y;

        var width = schemeObject.width;
        var height = schemeObject.height;
        if (!isHovered && !schemeObject.params.isSelected) {
            var borderOffset = 4;
            relativeX = relativeX + borderOffset;
            relativeY = relativeY + borderOffset;
            width = width - (borderOffset * 2);
            height = height - (borderOffset * 2);
        }
		
        var halfWidth = width / 2;
        var halfHeight = height / 2;

        var circleCenterX = relativeX + halfWidth;
        var circleCenterY = relativeY + halfHeight;

        if (schemeObject.rotation) {
            context.save();
            context.translate( relativeX + halfWidth, relativeY + halfHeight);
            context.rotate(schemeObject.rotation * Math.PI / 180);
            context.rect(-halfWidth, -halfHeight, width, height);
        } else {
            context.arc(circleCenterX, circleCenterY, halfWidth, 0, Math.PI * 2);
        }


        context.fill();
        context.stroke();

        context.font = -2 + (Math.floor((schemeObject.width + schemeObject.height) / 4)) + 'px Arial';

        if (schemeObject.params.isSelected && isHovered) {
            context.fillStyle = 'black';
        } else if (isHovered) {
            context.fillStyle = backgroundColor;
        } else if (schemeObject.params.isSelected) {
            context.fillStyle = 'black';
        }

            context.textAlign = 'center';
            context.textBaseline = 'middle';
            context.fillStyle = '#000';
            context.fillText(schemeObject.params.text,
                    -(schemeObject.width / 2) + (schemeObject.width / 2),
                    -(schemeObject.height / 2)  + (schemeObject.height / 2)
            );

        if (schemeObject.rotation) {
            context.restore();
        }
    };

    var clickOnPlace = function (schemeObject, schemeDesigner, view, e)
    {
        schemeObject.params.isSelected = !schemeObject.params.isSelected;

        let getIndex = placesSelected.indexOf(schemeObject.id)
        if(getIndex == -1) {
            placesSelected.push(schemeObject.id)
        } else {
            placesSelected = placesSelected.filter(function(e) { return e !== schemeObject.id })
        }
        console.log(placesSelected)
    };

    for (var i = 0; i < places.length; i++)
    {
        var objectData = places[i]
        var leftOffset = objectData.x >> 0;
        var topOffset = objectData.y >> 0;
        var width = objectData.width >> 0;
        var height = objectData.height >> 0;
        var rotation = 90;

        var schemeObject = new SchemeDesigner.SchemeObject({

            x: 0.5 + leftOffset,
            y: 0.5 + topOffset,
            width: width,
            height: height,
            active: 'Place',
            renderFunction: renderPlace,
            cursorStyle: 'default',

            rotation: rotation,
            text: `${objectData.id}`,
            row: objectData.id,
            backgroundColor: objectData.BackColor,
            fontColor: '#000',

            isSelected: false,
            clickFunction: clickOnPlace,
            
            clearFunction: function (schemeObject, schemeDesigner, view) {
                var context = view.getContext();

                var borderWidth = 5;
                context.clearRect(schemeObject.x - borderWidth,
                        schemeObject.y - borderWidth,
                        this.width + (borderWidth * 2),
                        this.height + (borderWidth * 2)
                );
            }
        });

        defaultLayer.addObject(schemeObject);
    }

    /**
     * add background object
     */
    backgroundLayer.addObject(new SchemeDesigner.SchemeObject({
        x: 0.5,
        y: 0.5,
        width: 8600,
        height: 7000,
        cursorStyle: 'default',
        renderFunction: function (schemeObject, schemeDesigner, view) {
            var context = view.getContext();
            context.beginPath();
            context.lineWidth = 4;
            context.strokeStyle = 'rgba(12, 200, 15, 0.2)';

            context.fillStyle = 'rgba(12, 200, 15, 0.2)';


            var width = schemeObject.width;
            var height = schemeObject.height;

            context.rect(schemeObject.x, schemeObject.y, width, height);


            context.fill();
            context.stroke();
        }
    }));

    var canvas = document.getElementById('canvas1');
    var mapCanvas = document.getElementById('canvas1_map');

    var schemeDesigner = new SchemeDesigner.Scheme(canvas, {
        options: {
            cacheSchemeRatio: 2
        },
        scroll: {
            maxHiddenPart: 0.5
        },
        zoom: {
            padding: 0.1,
            maxScale: 8,
            zoomCoefficient: 1.04
        },
        storage: {
            treeDepth: 6
        },
        map: {
            mapCanvas: mapCanvas
        }
    });

    /**
     * Adding layers with objects
     */
    schemeDesigner.addLayer(defaultLayer);
    schemeDesigner.addLayer(backgroundLayer);

    /**
     * Show scheme
     */
    schemeDesigner.render();



    canvas.addEventListener('schemeDesigner.beforeRenderAll', function (e) {
        console.time('renderAll');
    }, false);

    canvas.addEventListener('schemeDesigner.afterRenderAll', function (e) {
        console.timeEnd('renderAll');
    }, false);

    canvas.addEventListener('schemeDesigner.clickOnObject', function (e) {
        console.log('clickOnObject', e.detail);
    }, false);

    canvas.addEventListener('schemeDesigner.mouseOverObject', function (e) {
       // console.log('mouseOverObject', e.detail);
    }, false);

    canvas.addEventListener('schemeDesigner.mouseLeaveObject', function (e) {
      //  console.log('mouseLeaveObject', e.detail);
    }, false);

    canvas.addEventListener('schemeDesigner.scroll', function (e) {
      //  console.log('scroll', e.detail);
    }, false);
</script>
@stop