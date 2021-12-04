    $(function() {

        // when images being added to the input, this script should fire off
        $(document).on('change', 'input#upload-new-images', function(e){

            let $t, url, input, upload;

            $t = $(this),
            url = dynamicHost + '/_magic_/ajax/functions/manage/products/upload-images';
            
            // if the length of the file input is higher than 0, the fileupload
            // should fire off
            if($t.length > 0) {

                // the input to be passed in the upload function
                input = "#" + this.id;

                // start the actual upload by calling the function uploadFiles
                // and pass the url for the PHP script as well as the input that
                // contains the files
                upload = uploadFiles(url, input);
            }
        });
    });


    // the blueimp jquery file upload function
    let uploadFiles = function(url, input) {

        // start a file upload with the passed param of the file input
        $(input).fileupload({

            url: url,
            dataType: 'JSON',
            fileInput: $(input), // if I change this to just this without $(...) it works, but it resets my form on my page weirdly
            autoUpload: true,
            progressall: function(e, data) {

                // calculating progress and showing somewhere
            },
            done: function(e, data) {

                // give responsive feedback for every image uploaded
            },
            stop: function(e, data) {

                // give feedback when everything is done
            }
        });
    }