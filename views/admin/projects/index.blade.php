@extends('admin.master')
@section('content')
@section('title', 'إدارة المشاريع')

<div class="container">
    {{-- Header + إحصائيات --}}
    <div class="header-section mb-4">
        <div class="row align-items-center mb-4">
            <div class="col-lg-6">
                <h2 class="mb-2 fw-bold text-primary">إدارة المشاريع</h2>
                <p class="text-muted mb-0">إدارة ومراقبة جميع المشاريع في النظام</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="search-container">
                    <div class="input-group" style="width: 300px;">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input id="searchByName" type="text" placeholder="البحث في المشاريع..." class="form-control"
                            value="{{ $q ?? '' }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-section">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="stat-card card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="stat-icon bg-primary text-white rounded me-3 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 text-primary fw-bold">{{ $projects->total() }}</h4>
                                <small class="text-muted">إجمالي المشاريع</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="stat-icon bg-warning text-white rounded me-3 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 text-warning fw-bold">
                                    {{ $projects->where('status', 'pending')->count() }}</h4>
                                <small class="text-muted">في انتظار المراجعة</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="stat-icon bg-success text-white rounded me-3 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 text-success fw-bold">
                                    {{ $projects->where('status', 'approved')->count() }}</h4>
                                <small class="text-muted">مُعتمدة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table project-table">
                <thead>
                    <tr>
                        <th style="white-space: nowrap; min-width: 50px;">#</th>
                        <th style="white-space: nowrap; min-width: 140px;">صورة المشروع</th>
                        <th style="min-width: 150px;">اسم المشروع</th>
                        <th style="min-width: 120px;">نوع القطاع</th>
                        <th style="min-width: 120px;">اسم المقترض</th>
                        <th style="white-space: nowrap; min-width: 120px;">مبلغ التمويل </th>
                        <th style="white-space: nowrap; min-width: 120px;">المبلغ الممول </th>
                        <th style="white-space: nowrap; min-width: 100px;">نسبة الفائدة </th>
                        <th style="white-space: nowrap; min-width: 120px;">مدة السداد (بالأشهر)</th>
                        <th style="white-space: nowrap; min-width: 120px;">الحد الأدنى للاستثمار (ريال)</th>
                        <th style="min-width: 100px;">الحالة</th>
                        <th style="min-width: 200px;">ملخص </th>
                        <th style="min-width: 200px;">وصف مختصر</th>
                        <th style="white-space: nowrap; min-width: 100px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="categoriesTbody">
                    @include('admin.projects._rows', ['projects' => $projects])
                </tbody>

                <div id="categoriesPagination" class="mt-3">
                    {{-- {{ $projects->links() }} --}}
                </div>

            </table>
        </div>

        <!-- Modal for Add/Edit Project -->
        <div id="modalOverlay" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTitle">إضافة مشروع جديد</h5>
                    <button type="button" id="closeModalBtn" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="methodSpoof" value="">

                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان المشروع</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">القطاع</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">اختر القطاع</option>
                                @foreach (\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">ملخص المشروع</label>
                            <textarea class="form-control" id="summary" name="summary" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف المشروع</label>
                            <textarea class="form-control" id="editor" name="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="funding_goal" class="form-label">مبلغ التمويل المطلوب</label>
                            <input type="number" class="form-control" id="funding_goal" name="funding_goal"
                                step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="interest_rate" class="form-label">نسبة الفائدة (%)</label>
                            <input type="number" class="form-control" id="interest_rate" name="interest_rate"
                                step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="term_months" class="form-label">مدة السداد (بالأشهر)</label>
                            <input type="number" class="form-control" id="term_months" name="term_months" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">صورة المشروع الرئيسية</label>
                            <input type="file" class="form-control" id="image" name="image"
                                accept="image/*">
                            <img id="imagePreview" src="" alt="Preview"
                                style="max-width: 200px; margin-top: 10px; display: none;">
                        </div>

                        <div class="mb-3">
                            <label for="gallery" class="form-label">معرض الصور (يمكن اختيار عدة صور)</label>
                            <input type="file" class="form-control" id="gallery" name="imageproduct[]"
                                accept="image/*" multiple>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="cancelBtn" class="btn btn-secondary">إلغاء</button>
                            <button type="submit" id="saveBtn" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        // عناصر المودال والحقول
        const modalOverlay = document.getElementById('modalOverlay');
        const modalTitle = document.getElementById('modalTitle');
        const categoryForm = document.getElementById('categoryForm');
        const methodSpoof = document.getElementById('methodSpoof');

        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveBtn = document.getElementById('saveBtn');

        const titleInput = document.getElementById('title');
        const categoryInput = document.getElementById('category_id');
        const summaryInput = document.getElementById('summary');
        const fundingGoalInput = document.getElementById('funding_goal');
        const interestRateInput = document.getElementById('interest_rate');
        const termMonthsInput = document.getElementById('term_months');
        const descInput = document.getElementById('editor'); // textarea للوصف
        const imagePreview = document.getElementById('imagePreview');

        let currentProjectId = null;

        // فتح المودال
        function openModal(editMode = false, data = null) {
            modalOverlay.classList.add('active');

            if (editMode && data) {
                modalTitle.textContent = 'تعديل المشروع';
                currentProjectId = data.id;

                titleInput.value = data.title || '';
                categoryInput.value = data.category || '';
                summaryInput.value = data.summary || '';
                fundingGoalInput.value = data.funding_goal || '';
                interestRateInput.value = data.interest_rate || '';
                termMonthsInput.value = data.term_months || '';

                // الوصف
                if (window.CKEDITOR && CKEDITOR.instances.editor) {
                    CKEDITOR.instances.editor.setData(data.description || '');
                } else {
                    descInput.value = data.description || '';
                }

                // الفورم -> Update
                categoryForm.action = data.updateUrl;
                methodSpoof.value = 'PUT';

                // صورة
                if (imagePreview) {
                    imagePreview.src = data.image ? `/storage/${data.image}` : '';
                    imagePreview.style.display = data.image ? 'block' : 'none';
                }
            } else {
                modalTitle.textContent = 'إضافة مشروع جديد';
                currentProjectId = null;

                categoryForm.reset();

                if (window.CKEDITOR && CKEDITOR.instances.editor) {
                    CKEDITOR.instances.editor.setData('');
                } else {
                    descInput.value = '';
                }

                categoryForm.action = "{{ route('projects.store') }}";
                methodSpoof.value = '';

                if (imagePreview) {
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                }
            }
        }

        function closeModal() {
            modalOverlay.classList.remove('active');
        }

        // زر إضافة
        const openModalBtn = document.querySelector('.add-button');
        if (openModalBtn) {
            openModalBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal(false, null);
            });
        }

        // معاينة الصورة
        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                }
            });
        }

        // زر تعديل
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.edit-btn');
            if (!btn) return;

            e.preventDefault();

            const data = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                description: btn.dataset.description || '',
                image: btn.dataset.image,
                updateUrl: btn.dataset.updateUrl
            };

            openModal(true, data);
        });

        // إغلاق
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) closeModal();
        });

        // حفظ
        saveBtn.addEventListener('click', (e) => {
            e.preventDefault();

            if (window.CKEDITOR && CKEDITOR.instances.editor) {
                CKEDITOR.instances.editor.updateElement();
            }

            if (categoryForm.checkValidity()) {
                categoryForm.submit();
            } else {
                categoryForm.reportValidity();
            }
        });
    </script>


    <script>
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];

            $.ajax({
                url: '?page=' + page,
                success: function(data) {
                    $('#tableData').html(data);
                }
            });
        });
    </script>
    <script>
        (function() {
            const input = document.getElementById('searchByName');
            const tbody = document.getElementById('categoriesTbody');
            const pagerBox = document.getElementById('categoriesPagination');
            const baseIndex = "{{ route('projects.index') }}";

            let timer = null;

            function runSearch(url) {
                const finalUrl = new URL(url || baseIndex, window.location.origin);
                // ضمّن قيمة البحث الحالية في الرابط
                const q = (input?.value || '').trim();
                if (q !== '') finalUrl.searchParams.set('q', q);
                else finalUrl.searchParams.delete('q');

                // حالة تحميل بسيطة
                if (input) input.disabled = true;

                fetch(finalUrl.toString(), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (tbody && data.rows !== undefined) {
                            tbody.innerHTML = data.rows;
                        }
                        if (pagerBox && data.pagination !== undefined) {
                            pagerBox.innerHTML = data.pagination;
                        }
                        // حدّث شريط العنوان بدون إعادة تحميل
                        if (window.history && window.history.replaceState) {
                            window.history.replaceState({}, '', finalUrl.toString());
                        }
                    })
                    .catch(() => {
                        // تقدر تعرض Toast خطأ هنا لو عندك util
                        console.error('Search failed');
                    })
                    .finally(() => {
                        if (input) input.disabled = false;
                    });
            }

            // Debounce on input
            if (input) {
                input.addEventListener('input', function() {
                    clearTimeout(timer);
                    timer = setTimeout(() => runSearch(baseIndex), 300);
                });
            }

            // AJAX pagination (تفويض أحداث)
            document.addEventListener('click', function(e) {
                const a = e.target.closest('#categoriesPagination a');
                if (!a) return;
                e.preventDefault();
                runSearch(a.href);
            });


        })();
    </script>

    @section('js')
    @endsection
@endsection
