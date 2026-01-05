{{-- resources/views/admin/projects/_rows.blade.php --}}
@php
    use Illuminate\Support\Str;
@endphp

@forelse ($projects as $project)
    <tr>
        {{-- رقم تسلسلي --}}
        <td>{{ $loop->iteration }}</td>

        {{-- صورة المشروع --}}
        <td style="min-width: 140px;">
            <div id="carousel-{{ $project->id }}" class="carousel slide" data-bs-ride="carousel" style="width:120px;">
                <div class="carousel-inner">
                    @if ($project->image)
                        <div class="carousel-item active">
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->name }}"
                                style="width:120px; height:100px; object-fit:cover; border-radius:6px;">
                        </div>
                    @endif
                    @foreach ($project->images as $img)
                        <div class="carousel-item">
                            <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $project->name }}"
                                style="width:120px; height:100px; object-fit:cover; border-radius:6px;">
                        </div>
                    @endforeach
                </div>
                @if (count($project->images) > 0 || $project->image)
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $project->id }}"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $project->id }}"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </td>


        {{-- اسم المشروع --}}
        <td class="title">{{ $project->title }}</td>

        {{-- نوع القطاع (اسم وليس رقم) --}}
        <td class="category_id">
            {{ $project->category->name ?? 'غير محدد' }}
        </td>

        {{-- المقترض (اسم المستخدم) --}}
        <td class="borrower_id">
            {{ $project->borrower->name ?? '—' }}
        </td>

        {{-- مبلغ التمويل --}}
        <td class="funding_goal">{{ number_format($project->funding_goal) }}</td>

        {{-- المبلغ المُموَّل --}}
        <td class="funded_amount">{{ number_format($project->funded_amount) }}</td>

        {{-- نسبة الفائدة --}}
        <td class="interest_rate">{{ $project->interest_rate }}%</td>

        {{-- مدة السداد --}}
        <td class="term_months">{{ $project->term_months }} شهر</td>

        {{-- الحد الأدنى للاستثمار --}}
        <td class="min_investment">{{ number_format($project->min_investment) }}</td>
        {{-- الحالة --}}
        <td class="status">
            <div class="d-flex align-items-center">
                @php
                    $statuses = [
                        'draft' => ['class' => 'secondary', 'label' => 'مسودة'],
                        'pending' => ['class' => 'warning', 'label' => 'قيد المراجعة'],
                        'approved' => ['class' => 'success', 'label' => 'موافقة مبدئية'],
                        'funding' => ['class' => 'info', 'label' => 'مفتوح للاستثمار'],
                        'active' => ['class' => 'primary', 'label' => 'ممَوَّل بالكامل'],
                        'completed' => ['class' => 'success', 'label' => 'مرحلة السداد'],
                        'defaulted' => ['class' => 'danger', 'label' => 'متعثر'],
                    ];
                    $status = $statuses[$project->status] ?? ['class' => 'dark', 'label' => 'غير معروف'];
                @endphp

                <span id="badge-{{ $project->id }}" class="badge me-2 bg-{{ $status['class'] }}">
                    {{ $status['label'] }}
                </span>

                <form action="{{ route('admin.projects.updateStatus', $project->id) }}" method="POST"
                    style="margin:0;">
                    @csrf
                    @method('PATCH')

                    <select name="status" class="form-select form-select-sm" style="min-width: 160px;"
                        data-prev="{{ $project->status }}" onchange="handleStatusChange(this)">


                        @foreach ($statuses as $key => $item)
                            <option value="{{ $key }}" {{ $project->status === $key ? 'selected' : '' }}>
                                {{ $item['label'] }}
                            </option>
                        @endforeach

                    </select>
                </form>

            </div>
        </td>

        {{-- ملخص --}}
        <td class="summary">{{ $project->summary }}</td>

        {{-- وصف مختصر --}}
        <td class="description">
            {{ Str::limit(strip_tags($project->description), 50) }}
        </td>

        {{-- الأكشن --}}
        <td class="actions">


            {{-- زر حذف --}}
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                class="d-inline-block delete-form">
                @csrf
                @method('delete')
                <button type="submit" class="btn-action btn-delete" title="حذف">
                    <i class="fas fa-trash"></i>
                </button>
            </form>

        </td>
    </tr>

@empty
    <tr>
        <td colspan="13" class="text-center text-muted">لا توجد نتائج مطابقة.</td>
    </tr>
@endforelse
<script>
    function handleStatusChange(select) {
        const form = select.closest('form');
        const prev = select.dataset.prev;
        const newVal = select.value;

        const statuses = {
            draft: {
                class: 'secondary',
                label: 'مسودة'
            },
            pending: {
                class: 'warning',
                label: 'قيد المراجعة'
            },
            approved: {
                class: 'success',
                label: 'موافقة مبدئية'
            },
            funding: {
                class: 'info',
                label: 'مفتوح للاستثمار'
            },
            active: {
                class: 'primary',
                label: 'ممَوَّل بالكامل'
            },
            completed: {
                class: 'success',
                label: 'مرحلة السداد'
            },
            defaulted: {
                class: 'danger',
                label: 'متعثر'
            },
        };

        if (!confirm('هل أنت متأكد من تغيير حالة المشروع؟')) {
            select.value = prev;
            return;
        }

        // تحديث الشارة فوراً (UX فقط)
        const tr = select.closest('tr');
        const badge = tr.querySelector('[id^="badge-"]');

        if (badge && statuses[newVal]) {
            badge.className = 'badge me-2 bg-' + statuses[newVal].class;
            badge.textContent = statuses[newVal].label;
        }

        select.dataset.prev = newVal;
        form.submit();
    }
</script>
