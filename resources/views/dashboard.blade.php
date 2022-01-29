<html>

<head>
    <title>
        Slow Queries
    </title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <style>
        h1 {
            margin: 30px auto;
        }

        .collapsible {
            background-color: #fce4ec;
            border: 1px solid #f48fb1;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            text-align: left;
            outline: none;
            font-size: 15px;
            color: #212121;
        }

        .collapsible:hover {
            background-color: #ffebee;
        }

        button {
            filter: none !important;
        }

        .collapsible:after {
            content: '\002B';
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }

        .active:after {
            content: "\2212";
        }

        .content {
            padding: 0 18px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
            background-color: #fafafa;
        }

        code {
            display: block;
            background-color: #fafafa;
            padding: 1em;
            border-radius: 10px;
            margin: 10px auto;
            color: #212121;
        }
    </style>
</head>

<body>
    <h1>Slow Queries</h1>
    @foreach ($data as $row)
    <button class='collapsible'>
        <strong>{{ $row->time }}ms</strong> - {{ $row->path }}
    </button>
    <div class='content'>
        <code>
            {{ $row->sql }}
        </code>
        <dl>
            @foreach($row->traces as $trace)
            <dt>{{ $trace->function }}</dt>
            <dd>{{ $trace->file }} : <b>{{ $trace->line }}</b></dd>
            @endforeach
        </dl>
    </div>
    @endforeach

    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
        }
    </script>
</body>

</html>