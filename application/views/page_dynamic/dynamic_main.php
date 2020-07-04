<section class="hero" style="padding-top:50px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 w-50">
                <ul class="breadcrumbs">
                    <li><a href="<?php echo base_url()?>">Home</a></li>
                    <li><a href="<?php echo base_url('legitimate')?>">Test Legitimate Dataset</a></li>
                    <li>Legitimate Test List</li>
                </ul>
                <!-- <ul style="float:right;">
                            <form method="POST" action="<?php echo base_url('legitimate/deletealltask'); ?>">
                                <?php if(sizeof($task) > 0){?> <input name="analyze" type="submit" class="btn btn-danger btn-block btn-sm" value="Delete All Task"/> <?php } ?>
                            </form>
                </ul> -->
                
            </div>
        </div>
    </div>
</section>
<section class="hero" style="min-height:400px;">
    <div class="container">
        <!-- <div class="columns" style="padding-top:20px;">
                <div class="field is-grouped">
                <p class="control is-expanded">
                    <input class="X" id="inputurl" type="text" name="urls" placeholder="Masukkan URL/ Domain Website" 
                    style="width:100%;" 
                    required>
                </p>
                <p class="control">
                    <input name="analyze" type="submit" id="scanning" class="button is-info" value="Scan Website"/> 
                </p>
            </div>
        </div> -->
        <br/>
        <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="input-group mb-3">
                            <input class="form-control" id="inputurl" type="text" placeholder="Masukkan URL/ Domain Website">
                            <div class="input-group-append">
                                <button id="scanning" class="btn btn-outline-secondary">Scan Website</button>
                            </div>
                        </div>
                </div>
        </div>
        <br/>
        <br/>
        <center>
            <div class="loader" style="display:none;"></div>
            <div id="status" style="display:none;margin-top:10px;margin-bottom:10px;">Mohon Tunggu</div>

            <div id="notif" style="display:none;margin-top:10px;margin-bottom:10px;" class="alert alert-warning alert-dismissible fade show" role="alert">
            Proses <strong>scanning & analyzing </strong> berhasil.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <br/>
            <br/>
            <br/>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                        <table  id="table1" class="table table-striped table-bordered responsive ">
                            <thead>
                                <tr>
                                    <th>Scan Id</th>
                                    <th>Link URL</th>
                                    <th>HTML File</th>
                                    <th>Tanggal ditambahkan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php if(isset($scan)) {?>
                                        <?php foreach($scan as $item) { ?>
                                            <tr>
                                                <td style="background:#ececec;text-align:left;"><?php echo $item->scan_id; ?></td>
                                                <td title="<?php echo $item->dataset_url; ?>" style="color:#3c70a4;text-align:left;"><?php echo $item->dataset_url;?></td>
                                                <td title="<?php echo $item->dataset_html_file; ?>" style="color:#3c70a4;text-align:left;"><?php echo $item->dataset_html_file; ?></td>
                                                <td style="text-align:left;"><?php echo $item->date_created; ?></td>
                                                <td><a href="<?php echo base_url(); ?>mainthree/delete/<?php echo  $item->scan_id; ?>" class="btn btn-danger btn-sm">Delete</a></td>
                                            </tr>
                                            <?php } ?> 
                                    <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
            <br/><br/>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h6 style="text-align:left;">Independent Feature Detection</h6>
                        <table  id="table3" class="table table-striped table-bordered responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Scan Id</th>
                                    <th>Protocol</th>
                                    <th>Symbols</th>
                                    <th>Length</th>
                                    <th>Dot</th>
                                    <th>Sensitive</th>
                                    <th>Login</th>
                                    <th>Empty</th>
                                    <th>Size</th>
                                    <th>Redirect</th>
                                    <th>Iframe</th>
                                    <th>Favicon</th>
                                    <th>Double Top Domain</th>
                                    <th>Shortlink</th>
                                    <th>Cheap Domain</th>
                                    <th>Total Path</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php if(isset($features)) {?>
                                        <?php foreach($features as $item) { ?>
                                            <tr>
                                                <td style="text-align:left;"><?php echo $item->scan_id; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_protocol; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_symbol; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_length; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_dot_total; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_sensitive_char; ?></td>
                                                <td style="text-align:left;"><?php echo $item->html_login; ?></td>
                                                <td style="text-align:left;"><?php echo $item->html_empty_link; ?></td>
                                                <td style="text-align:left;"><?php echo $item->html_length; ?></td>
                                                <td style="text-align:left;"><?php echo $item->html_redirect; ?></td>
                                                <td style="text-align:left;"><?php echo $item->html_iframe; ?></td>
                                                <td style="text-align:left;"><?php echo $item->html_favicon; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_doubletopdomain; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_shortlink; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_domain_murah; ?></td>
                                                <td style="text-align:left;"><?php echo $item->url_totalpath; ?></td>
                                            </tr>
                                            <?php } ?> 
                                    <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                        <h6 style="text-align:left;">Combine Feature Detection</h6>
                        <table  id="table4" class="table table-striped table-bordered responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sc.Id</th>
                                    <th>Rule A</th>
                                    <th>Rule B</th>
                                    <th>Rule C</th>
                                    <th>Rule D</th>
                                    <th>Rule E</th>
                                    <th>Result</th>
                            </thead>
                            <tbody>
                            <?php if(isset($features)) {?>
                                        <?php foreach($smart_features as $item) { ?>
                                            <tr>
                                                <td style="text-align:left;"><?php echo $item->scan_id; ?></td>
                                                <td style="text-align:left;"><?php echo $item->rule_a; ?></td>
                                                <td style="text-align:left;"><?php echo $item->rule_b; ?></td>
                                                <td style="text-align:left;"><?php echo $item->rule_c; ?></td>
                                                <td style="text-align:left;"><?php echo $item->rule_d; ?></td>
                                                <td style="text-align:left;"><?php echo $item->rule_e; ?></td>
                                                <td style="text-align:left;"><?php echo $item->result; ?></td>
                                            </tr>
                                            <?php } ?> 
                                    <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </center>
    
    </div>
</section>
<script>
    $(document).ready(function(){   
            let scanbutton = $('#scanning');
            scanbutton.on('click', function(){
                    // alert($('#inputurl').val());
                    $('.loader').show();
                    $('#status').show();
                    $.ajax({
                        url: "<?php echo base_url(); ?>scan", 
                        method : 'POST',
                        data : {
                            urls : $('#inputurl').val()
                        },
                        success: function(result){
                            console.log(result);
                            if(result == 'ok' || result){
                                console.log(result);
                                setTimeout(function(){
                                    $('.loader').hide();
                                    $('#status').hide();
                                    $('#notif').show();
                                    location.reload();
                                }, 1000);
                            }else{
                                console.log(result);
                            }
                        },
                        error : function(result){
                            console.log('gagal broh');
                            $('#status').text('Gagal');
                            $('.loader').hide();
                            $('#status').hide();
                        }
                    });
                });
            // scanbutton.attr('disabled', true);
            // $('#inputurl').on('change', function(){
            //     alert($(this).val());
            //         let get_data_input = $('#inputurl').val();
            //         let checking_input = get_data_input == "" ? true : false;
            //         if(checking_input){
            //             alert('Data kosong');
            //         }else{
            //             scanbutton.attr('disabled', false);
            //             scanbutton.on('click', function(){
            //                 alert($('#inputurl').val());
            //                 $('.loader').show();
            //                 $('#status').show();
            //                 $.ajax({
            //                     url: "<?php echo base_url(); ?>scan", 
            //                     method : 'POST',
            //                     data : {
            //                         urls : $('#inputurl').val()
            //                     },
            //                     success: function(result){
            //                         if(result == 'ok' || result){
            //                             setTimeout(function(){
            //                                 $('.loader').hide();
            //                                 $('#status').hide();
            //                                 $('#notif').show();
            //                                 location.reload();
            //                             }, 3000);
            //                         }else{
            //                             console.log(result);
            //                         }
            //                     },
            //                     error : function(result){
            //                         console.log('gagal broh');
            //                         $('#status').text('Gagal');
            //                         $('.loader').hide();
            //                         $('#status').hide();
            //                     }
            //                 });
            //             });
            //         }
            // });

            
            $('#table1').DataTable( {
                responsive : true,
                dom: 'Bfrtip',
                order: [[0, "desc" ]],
                buttons: [
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        exportOptions: {
                            modifier: {
                            }
                        }
                    }
                ]
            } );

            $('#table3').DataTable({
                order: [[ 0, "desc" ]],
                responsive : true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        exportOptions: {
                            modifier: {
                            }
                        }
                    }
                ]
            } );


            $('#table4').DataTable({
                order: [[ 0, "desc" ]],
                responsive : true,
                dom: 'Bfrtip',
                buttons: [
                    {   
                        extend: 'csv',
                        text: 'Export CSV',
                        exportOptions: {
                            modifier: {
                            }
                        }
                    }
                ]
            } );
                    
    });
</script>