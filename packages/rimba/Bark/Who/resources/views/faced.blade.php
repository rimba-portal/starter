@php

$config = $getConfiguration();

@endphp


<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>


<div

    x-data="faceVerification(@js($config))"

    x-init="init()"

    class="
        rounded-lg
        border
        bg-gray-50
        p-4
    "

>


{{-- ========================================================= --}}
{{-- Camera                                                   --}}
{{-- ========================================================= --}}


<video

    x-ref="video"

    autoplay

    muted

    playsinline

    class="
        h-32
        w-32
        rounded-lg
        bg-black
        object-cover
    "

></video>



{{-- ========================================================= --}}
{{-- Status                                                   --}}
{{-- ========================================================= --}}


<div class="mt-3 text-sm">

    <span x-text="status"></span>

</div>



</div>


</x-dynamic-component>



@once


<style>

.face-success {

    border-color: green;

}


</style>



<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>



<script>


document.addEventListener(
    'alpine:init',
    () => {


        Alpine.data(
            'faceVerification',
            (config)=>({


                /*
                |--------------------------------------------------------------------------
                | Config
                |--------------------------------------------------------------------------
                */


                config,


                /*
                |--------------------------------------------------------------------------
                | State
                |--------------------------------------------------------------------------
                */


                status:'Waiting',

                stream:null,

                matched:false,


                /*
                |--------------------------------------------------------------------------
                | Init
                |--------------------------------------------------------------------------
                */


                async init()
                {

                    console.log(
                        'Face Verification',
                        this.config
                    );


                    if(
                        this.config.autoStart
                    ){

                        await this.startCamera();

                    }

                },



                /*
                |--------------------------------------------------------------------------
                | Camera
                |--------------------------------------------------------------------------
                */


                async startCamera()
                {

                    try {


                        this.stream =
                            await navigator
                            .mediaDevices
                            .getUserMedia({

                                video:true

                            });



                        this.$refs.video.srcObject =
                            this.stream;


                        this.status =
                            'Camera Ready';


                    }

                    catch(error)
                    {

                        this.status =
                            'Camera Error';

                    }


                },



                stopCamera()
                {

                    this.stream
                    ?.getTracks()
                    .forEach(
                        track=>track.stop()
                    );


                }



            })

        );


    }

);


</script>


@endonce