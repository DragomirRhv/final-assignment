;(function(win,doc,$){

    $(document).ready(function() {
        var $task = $('.clickIT');
        var $invisible_welcome = $('.invsible');
        var $form = $('.frame');
        var $button = $('.button');
        var $stamp = $('.stamp');

        $task.on('click',function(){
            if($invisible_welcome.hasClass('visible') || $task.hasClass('hanging') || $form.hasClass('show'))  {
                $invisible_welcome.removeClass('visible');
                $task.removeClass('hanging');
                $form.removeClass('show');
                return;
            }
            $invisible_welcome.addClass('visible');
            $task.addClass('hanging');
            $form.addClass('show');
        });

        $button.on('click',function(){
            $stamp.addClass('dissappear');
        });
        
    });

})(window,document,jQuery)