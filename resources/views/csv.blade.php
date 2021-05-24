<table>
    <tbody>
    @foreach ($data as  $value)
        <tr>
            @foreach($value as $val)
                <td style="text-align: center">{{$val}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
