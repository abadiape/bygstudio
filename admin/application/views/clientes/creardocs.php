<h1><?php echo $title ?></h1>
<form method="post">
    <p>
            <br/>
            <br/>
            <br/>
            <textarea name="editor1"></textarea>
            <script>
                CKEDITOR.replace( 'editor1', {
    			toolbar: 'null',
    			toolbarGroups:'null',
    			uiColor: '#9AB8F3'
		});
            </script>
        </p>
        <p>
            <input type="submit" value="Enviar">
        </p>
</form>