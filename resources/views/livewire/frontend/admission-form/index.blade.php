<div class="container my-5">
    <div class="form-section">
        <h2 class="text-center">ভর্তি ফরম</h2>
        <form action="#" method="POST" enctype="multipart/form-data">

            <!-- নাম -->
            <div class="mb-3">
                <label for="name" class="form-label">পূর্ণ নাম</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="আপনার পূর্ণ নাম লিখুন" required>
            </div>

            <!-- জন্মতারিখ -->
            <div class="mb-3">
                <label for="dob" class="form-label">জন্মতারিখ</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
            </div>

            <!-- শ্রেণি -->
            <div class="mb-3">
                <label for="class" class="form-label">শ্রেণি নির্বাচন করুন</label>
                <select class="form-select" id="class" name="class" required>
                    <option value="">-- নির্বাচন করুন --</option>
                    <option value="play">প্লে-গ্রুপ</option>
                    <option value="one">প্রথম শ্রেণি</option>
                    <option value="two">দ্বিতীয় শ্রেণি</option>
                    <option value="three">তৃতীয় শ্রেণি</option>
                    <option value="four">চতুর্থ শ্রেণি</option>
                    <option value="five">পঞ্চম শ্রেণি</option>
                    <option value="six">ষষ্ঠ শ্রেণি</option>
                    <option value="seven">সপ্তম শ্রেণি</option>
                    <option value="eight">অষ্টম শ্রেণি</option>
                    <option value="nine">নবম শ্রেণি</option>
                    <option value="ten">দশম শ্রেণি</option>
                </select>
            </div>

            <!-- লিঙ্গ -->
            <div class="mb-3">
                <label class="form-label">লিঙ্গ</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                    <label class="form-check-label" for="male">পুরুষ</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" required>
                    <label class="form-check-label" for="female">মহিলা</label>
                </div>
            </div>

            <!-- অভিভাবকের নাম -->
            <div class="mb-3">
                <label for="parentName" class="form-label">অভিভাবকের নাম</label>
                <input type="text" class="form-control" id="parentName" name="parent_name" placeholder="অভিভাবকের নাম লিখুন" required>
            </div>

            <!-- মোবাইল নাম্বার -->
            <div class="mb-3">
                <label for="mobile" class="form-label">অভিভাবকের মোবাইল নাম্বার</label>
                <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="০১XXXXXXXXX" required>
            </div>

            <!-- ঠিকানা -->
            <div class="mb-3">
                <label for="address" class="form-label">বর্তমান ঠিকানা</label>
                <textarea class="form-control" id="address" name="address" rows="3" placeholder="ঠিকানা লিখুন" required></textarea>
            </div>

            <!-- পূর্ববর্তী স্কুল -->
            <div class="mb-3">
                <label for="previousSchool" class="form-label">পূর্ববর্তী স্কুল (যদি থাকে)</label>
                <input type="text" class="form-control" id="previousSchool" name="previous_school" placeholder="পূর্ববর্তী স্কুলের নাম">
            </div>

            <!-- ছবি আপলোড -->
            <div class="mb-3">
                <label for="photo" class="form-label">ছবি আপলোড করুন</label>
                <input class="form-control" type="file" id="photo" name="photo" accept="image/*" required>
            </div>

            <!-- সাবমিট বাটন -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-custom">ফরম জমা দিন</button>
            </div>

        </form>

        <!-- ডাউনলোড অপশন -->
        <div class="download-card mt-4">
            <h5>অফলাইনে ভর্তি ফরম পূরণ করতে চান?</h5>
            <p>আপনি চাইলে PDF ফরম ডাউনলোড করে হাতে পূরণ করে স্কুল অফিসে জমা দিতে পারবেন।</p>
            <a href="admission-form.pdf" class="btn btn-success btn-custom" download>📥 ভর্তি ফরম ডাউনলোড করুন</a>
        </div>
    </div>
</div>