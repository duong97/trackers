<?php
$this->registerCss("
    .accordion {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 9px;
        padding-right: 32px;
        width: 100%;
        text-align: left;
        border: none;
        outline: none;
        transition: 0.4s;
    }
    .accordion::after{
        content: '-';
        font-weight: bold;
        color: #777;
        margin-left: 5px;
        position: absolute;
        right: 10px;
        top: 30%;
    }
    .accordion.collapsed::after{ content:'+'; }
    .collapse-panel{
        margin-left: 40px;
        padding-left: 10px;
        border-left: 2px solid #1f919c;
    }
    .article-title-container{ position: relative;}
    .article-title-container input[type=checkbox]{ 
        position: absolute; 
        top: 0;
        left: -20px; 
        width: 20px;
        height: 20px;
    }
");
?>
<div class="container" id="docx"
     style="width:210mm; border: 1px solid black; padding: 30px 80px;">
    <div class="WordSection1">
        <form>
            <div style="font-weight: bold; text-align: center;">
                <p>CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                <p>Độc lập – Tự do – Hạnh phúc</p>
                <p>-------------------</p>
                <p>HỢP ĐỒNG CHUYỂN NHƯỢNG QUYỀN SỬ DỤNG ĐẤT VÀ TÀI SẢN GẮN LIỀN VỚI ĐẤT</p>
                <p>(Số: ................/HĐCNQSDĐ,TSGLĐ)</p>
            </div>

            <div>
                <p>Hôm nay, ngày <input style="width: 40px"> tháng <input style="width: 40px"> năm <input style="width: 40px"> Tại: <input style="width: 300px"><p>
                <p>Chúng tôi gồm có:</p>

                <p><b>BÊN CHUYỂN NHƯỢNG (BÊN A):</b></p>
                <p>Ông/bà: <input style="width: 400px"> Năm sinh: <input style="width: 100px">
                <p>CMND số: <input style="width: 200px"> Ngày cấp <input style="width: 115px"> Nơi cấp <input style="width: 120px"></p>
                <p>Hộ khẩu: <input style="width: 567px"></p>
                <p>Địa chỉ: <input style="width: 576px"></p>
                <p>Điện thoại: <input style="width: 556px"></p>

                <p><b>BÊN NHẬN CHUYỂN NHƯỢNG (BÊN B):</b></p>
                <p>Ông/bà: <input style="width: 400px"> Năm sinh: <input style="width: 100px">
                <p>CMND số: <input style="width: 200px"> Ngày cấp <input style="width: 115px"> Nơi cấp <input style="width: 120px"></p>
                <p>Hộ khẩu: <input style="width: 567px"></p>
                <p>Địa chỉ: <input style="width: 576px"></p>
                <p>Điện thoại: <input style="width: 556px"></p>

                <div class="artical-container">
                    <p class="article-title-container">
                        <input type="checkbox" class="artical-checkbox" checked="true">
                        <button class="accordion collapsed" type="button" data-toggle="collapse" data-target="#article1">
                            <b>ĐIỀU 1: VIỆC ĐĂNG KÝ TẶNG CHO QUYỀN SỬ DỤNG ĐẤT VÀ TÀI SẢN GẮN LIỀN VỚI ĐẤT VÀ LỆ PHÍ</b>
                        </button>
                    </p>
                    <div class="collapse collapse-panel" id="article1">
                        <div class="card card-body">
                            <p>1.1. Việc đăng ký chuyển nhượng quyền sử dụng đất và tài sản gắn liền với đất tại cơ quan có thẩm quyền theo quy định của pháp luật do bên A chịu trách nhiệm thực hiện.</p>
                            <p>1.2. Lệ phí liên quan đến việc chuyển nhượng quyền sử dụng đất và tài sản gắn liền với đất theo Hợp đồng này do bên A chịu trách nhiệm nộp.</p>
                        </div>
                    </div>
                </div>

                <div class="artical-container">
                    <p class="article-title-container">
                        <input type="checkbox" class="artical-checkbox" checked="true">
                        <button class="accordion collapsed" type="button" data-toggle="collapse" data-target="#article2">
                            <b>ĐIỀU 2: QUYỀN VÀ NGHĨA VỤ CỦA BÊN A</b>
                        </button>
                    </p>
                    <div class="collapse collapse-panel" id="article2">
                        <div class="card card-body">
                            <p>2.1. Nghĩa vụ của bên A:</p>
                            <p>a) Chuyển giao đất, tài sản gắn liền với đất cho bên B đủ diện tích, đúng hạng đất, loại đất, vị trí, số hiệu, tình trạng đất và tài sản gắn liền với đất như đã thoả thuận;</p>
                            <p>b) Giao giấy tờ có liên quan đến quyền sử dụng đất, quyền sở hữu tài sản gắn liền với đất cho bên B.</p>
                            <p>2.2. Quyền của bên A:</p>
                            <p>Bên A có quyền được nhận tiền chuyển nhượng quyền sử dụng đất, tài sản gắn liền với đất; trường hợp bên B chậm trả tiền thì bên A có quyền:</p>
                            <p>a) Gia hạn để bên B hoàn thành nghĩa vụ; nếu quá thời hạn này mà nghĩa vụ vẫn chưa được hoàn thành thì theo yêu cầu của bên A, bên B vẫn phải thực hiện nghĩa vụ và bồi thường thiệt hại;</p>
                            <p>b) Bên B phải trả lãi đối với số tiền chậm trả theo lãi suất cơ bản do Ngân hàng Nhà nước công bố tương ứng với thời gian chậm trả tại thời điểm thanh toán.</p>
                        </div>
                    </div>
                </div>
            </div>

            <table width="100%;" height="200px" >
                <td>
                    <p><b>BÊN A</b></p>
                    <p>(Ký, điểm chỉ và ghi rõ họ tên)</p>
                </td>
                <td>
                    <p><b>BÊN A</b></p>
                    <p>(Ký, điểm chỉ và ghi rõ họ tên)</p>
                </td>
            </table>
        </form>
    </div>
</div>

<div class="container" style="width:210mm;width: 210mm; padding: 0; margin-top: 17px;">
    <p class="btn btn-success" id="toWordBtn">Export to Word</p>
</div>


<script>
    $(function(){
        $('#toWordBtn').on('click', function(){
            var elm = $("#docx");
            toWord(elm);
        });
        $('.accordion').on('click', function(){
            
        });
    });
    function handleCheckbox(){
        $('.artical-checkbox').each(function(){
            if(!$(this).prop('checked')){
                $(this).closest('.artical-container').addClass('docxRemove');
            }
        });
    }
    function toWord(element) {
        handleCheckbox();
        if (!window.Blob) {
           alert('Your legacy browser does not support this action.');
           return;
        }
        var html, link, blob, url, css;

        // EU A4 use: size: 841.95pt 595.35pt;
        // US Letter use: size:11.0in 8.5in;

        css = (
          '<style>' +
          '@page WordSection1{size: 841.95pt 595.35pt;mso-page-orientation: landscape;}' +
          'div.WordSection1 {page: WordSection1;}' +
          '</style>'
        );

        html = element.html();
        var clone = $('#docx').clone();
        clone.find('.docxRemove').remove();
        html = clone.html();
        blob = new Blob(['\ufeff', css + html], {
          type: 'application/msword'
        });
        url = URL.createObjectURL(blob);
        link = document.createElement('A');
        link.href = url;
        // Set default file name. 
        // Word will append file extension - do not add an extension here.
        link.download = 'HomeContract.docx';   
        document.body.appendChild(link);
        if (navigator.msSaveOrOpenBlob ) navigator.msSaveOrOpenBlob( blob, 'Document.doc'); // IE10-11
            else link.click();  // other browsers
        document.body.removeChild(link);
    };

</script>