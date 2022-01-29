<html>

<head>
    <title>
        Slow Queries
    </title>
    <style>
        .collapsible {
            background-color: #777;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
        }

        .active,
        .collapsible:hover {
            background-color: #555;
        }

        .collapsible:after {
            content: '\002B';
            color: white;
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
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <h1>Hello there Im good as well</h1>
    @foreach ($data as $row)
    <button class='collapsible'>
        {{ $row->time }}ms - {{ $row->path }}
    </button>
    <div class='content'>
        <dl>
            @foreach($row->traces as $trace)
            <dt>{{ $trace->function }}</dt>
            <dd>{{ $trace->file }} : <b>{{ $trace->line }}</b></dd>
            @endforeach
        </dl>
    </div>
    @endforeach
</body>

</html>