@foreach($history_data as $row)
<tr>
    <th class="vcenter text-left">
        <table class="table table-condensed table-bordered no-padding-margin-b"

            @php
            $extra_fields = (array) json_decode($row->extra_field, true);
            $count = count($extra_fields);
                // $w_form_type_history = \App\WFormTypeHistory::where('reference_number', $dataForView['reference_number'])->get();
            @endphp
            @if (!empty($extra_fields['P']))
                <tr class="quotd">
                    <th class="quotd" colspan="6">
                        <h5>Passport</h5>
                    </th>
                </tr>
                @php
                    unset($extra_fields['P']['request_type']);
                    unset($extra_fields['P']['customer_id']);
                    unset($extra_fields['P']['response']);
                    $count1 = count($extra_fields['P']);
                @endphp
                @foreach ($extra_fields['P'] as $key => $r)
                    @php
                        $m_value = false;
                        unset($r['api_key']);
                    @endphp
                    @foreach ($r as $key1 => $value)
                        @if ($x == 1)
                            <tr class="quotd">
                        @endif

                        <th class="quotd">{{ $key1 }}</th>
                        <td class="quotd" @if ($m_value == 'true') style="background-color:#97333352" @endif>
                            {{ isset($value) ? $value : '' }}</td>

                        @if ($x == 3)
                            </tr>
                            <?php $x = 0; ?>
                        @elseif($count1 == 1)
                            @if ($x == 1)
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                </tr>
                            @elseif($x == 2)
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                </tr>
                            @else
                                </tr>
                            @endif
                        @endif
                        <?php $x++;
                        $count1--; ?>
                    @endforeach
                @endforeach
            @endif
            @if (!empty($extra_fields['C']))
                <tr class="quotd">
                    <th class="quotd" colspan="6">
                        <h5>Current Year</h5>
                    </th>
                </tr>
                @php
                    unset($extra_fields['C']['request_type']);
                    unset($extra_fields['C']['quota_id']);
                    unset($extra_fields['C']['customer_info']);
                    unset($extra_fields['C']['response']);
                    $count2 = count($extra_fields['C']);
                @endphp
                @foreach ($extra_fields['C'] as $key => $r)
                    @php
                        $m_value = false;
                        unset($r['api_key']);
                    @endphp
                    @foreach ($r as $key1 => $value)

                        @if ($y == 1)
                            <tr class="quotd">
                        @endif

                        <th class="quotd">{{ $key1 }}</th>
                        <td class="quotd" @if ($m_value == 'true') style="background-color:#97333352" @endif>
                            {{ isset($value) ? $value : '' }}</td>

                        @if ($y == 3)
                            </tr>
                            <?php $y = 0; ?>
                        @elseif($count2 == 1)
                            @if ($y == 1)
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                </tr>
                            @elseif($y == 2)
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                </tr>
                            @else
                                </tr>
                            @endif
                        @endif
                        <?php $y++;
                        $count2--; ?>
                    @endforeach
                @endforeach
            @endif

            @if (!empty($extra_fields['N']))
                <tr class="quotd">
                    <th class="quotd" colspan="6">
                        <h5>Next Year</h5>
                    </th>
                </tr>
                @php
                    unset($extra_fields['N']['request_type']);
                    unset($extra_fields['N']['quota_id']);
                    unset($extra_fields['N']['customer_info']);
                    unset($extra_fields['N']['response']);
                    $count3 = count($extra_fields['N']);
                @endphp
                @foreach ($extra_fields['N'] as $key => $r)
                    @php
                        $m_value = false;
                        unset($r['api_key']);
                    @endphp
                    @foreach ($r as $key1 => $value)

                        @if ($z == 1)
                            <tr>
                        @endif

                        <th class="quotd">{{ $key1 }}</th>
                        <td class="quotd" @if ($m_value == 'true') style="background-color:#97333352" @endif>
                            {{ isset($value) ? $value : '' }}</td>

                        @if ($z == 3)
                            </tr>
                            <?php $z = 0; ?>
                        @elseif($count3 == 1)
                            @if ($z == 1)
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                </tr>
                            @elseif($z == 2)
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                </tr>
                            @else
                                </tr>
                            @endif
                        @endif
                        <?php $z++;
                        $count3--; ?>
                    @endforeach
                @endforeach
            @endif

            @if (!empty($extra_fields['MQ']))
                <tr class="quotd">
                    <th class="quotd" colspan="6">
                        <h5>Medical Quota</h5>
                    </th>
                </tr>
                @php
                    unset($extra_fields['MQ']['request_type']);
                    unset($extra_fields['MQ']['quota_id']);
                    unset($extra_fields['MQ']['customer_info']);
                    unset($extra_fields['MQ']['response']);
                    $count3 = count($extra_fields['MQ']);
                @endphp
                @foreach ($extra_fields['MQ'] as $key => $r)
                    @php
                        $m_value = false;
                        unset($r['api_key']);
                    @endphp
                    @foreach ($r as $key1 => $value)
                        @if ($z == 1)
                            <tr class="quotd">
                        @endif

                        <th class="quotd">{{ $key1 }}</th>
                        <td class="quotd" @if ($m_value == 'true') style="background-color:#97333352" @endif>
                            {{ isset($value) ? $value : '' }}</td>

                        @if ($z == 3)
                            </tr>
                            <?php $z = 0; ?>
                        @elseif($count3 == 1)
                            @if ($z == 1)
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                </tr>
                            @elseif($z == 2)
                                <th class="quotd">&nbsp;</th>
                                <td class="quotd">&nbsp;</td>
                                </tr>
                            @else
                                </tr>
                            @endif
                        @endif
                        <?php $z++;
                        $count3--; ?>
                    @endforeach
                @endforeach
            @endif
        </table>
    </th>
    <th>{{$row->check_list}}</th>
    <th class="vcenter text-left border">{{$row->name}}</th>
    <th class="vcenter text-left border">{{$row->created_at}}</th>
</tr>
@endforeach
