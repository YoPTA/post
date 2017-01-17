<?php
$pagetitle = '�������� ���������� ����';
$page_id = 'page_index';

//���������� �����
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/company/company_index?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page<?= $page ?>">
            &#8592; ��������� � ������������
        </a>
    </div>
    <br /><br />

<?php if (isset($errors) && is_array($errors)): ?>
    <div class="error font_size_twelve">
        <div class="er"></div>
        <h2 align="center">������ �����</h2>

        <ul>
            <?php foreach ($errors as $error): ?>
                <li class="type_none"> - <?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <br /><br />
<?php endif; ?>


    <form method="POST">
        <label for="lastname">�������</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="lastname_correct"></span><br />
        <input type="text" placeholder="�������" id="lastname" name="lastname" class="quarter <?php if (isset($errors['lastname'])) echo 'error'; ?>" value="<?= $proxy_person['lastname'] ?>"
               onblur="InputCount('lastname', 'quarter', 128, 'lastname_correct', '������� �� ����� ���� ����� �����', '���������� ��������� �������', '')"
            /><br /><br />

        <label for="firstname">���</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="firstname_correct"></span><br />
        <input type="text" placeholder="���" id="firstname" name="firstname" class="quarter <?php if (isset($errors['firstname'])) echo 'error'; ?>" value="<?= $proxy_person['firstname'] ?>"
               onblur="InputCount('firstname', 'quarter', 64, 'firstname_correct', '��� �� ����� ���� ����� �����', '���������� ��������� ���', '')"
            /><br /><br />

        <label for="middlename">�������� (�������������)</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="middlename_correct"></span><br />
        <input type="text" placeholder="�������� (�������������)" id="middlename" name="middlename" class="quarter <?php if (isset($errors['middlename'])) echo 'error'; ?>" value="<?= $proxy_person['middlename'] ?>"
               onblur="InputCountCanEmpty('middlename', 'quarter', 128, 'middlename_correct', '�������� �� ����� ���� ����� �����', '')"
            /><br /><br />

        <label for="document_series">����� ��������</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="document_series_correct"></span><br />
        <input type="text" placeholder="����� ��������" id="document_series" name="document_series" class="one_eighth <?php if (isset($errors['document_series'])) echo 'error'; ?>" value="<?= $proxy_person['document_series'] ?>"
               onblur="InputCount('document_series', 'one_eighth', 4, 'document_series_correct', '����� �������� �� ����� ���� ����� �����', '���������� ��������� ����� ��������', '')"
            /><br /><br />

        <label for="document_number">����� ��������</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="document_number_correct"></span><br />
        <input type="text" placeholder="����� ��������" id="document_number" name="document_number" class="one_eighth <?php if (isset($errors['document_number'])) echo 'error'; ?>" value="<?= $proxy_person['document_number'] ?>"
               onblur="InputCount('document_number', 'one_eighth', 6, 'document_number_correct', '����� �������� �� ����� ���� ����� �����', '���������� ��������� ����� ��������', '')"
            /><br /><br />

        <label for="date_issued">���� ������</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="date_issued_correct"></span><br />
        <input type="text" placeholder="���� ������" id="date_issued" name="date_issued" class="tcal quarter" value="<?= $proxy_person['date_issued'] ?>"

            /><br /><br />

        <label for="place_name">����� ������</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="place_name_correct"></span><br />
        <textarea placeholder="����� ������" class="quarter <?php if (isset($errors['place_name'])) echo 'error'; ?>" id="place_name" name="place_name" rows="5"
                  onblur="InputCount('place_name', 'quarter', 256, 'place_name_correct', '����� ������ �� ����� ���� ����� �����', '���������� ��������� ����� ������', '')"
            ><?= $proxy_person['place_name'] ?></textarea><br /><br />

        <label for="place_code">��� ������</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="place_code_correct"></span><br />
        <input type="text" placeholder="��� ������" id="place_code" name="place_code" class="one_eighth <?php if (isset($errors['place_code'])) echo 'error'; ?>" value="<?= $proxy_person['place_code'] ?>"
               onblur="InputCount('place_code', 'one_eighth', 7, 'place_code_correct', '��� ������ �� ����� ���� ����� �����', '���������� ��������� ��� ������', '')"
            /><br /><br />

        <label for="phone_number">����� ��������</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="phone_number_correct"></span><br />
        <input type="text" placeholder="����� ��������" id="phone_number" name="phone_number" class="quarter <?php if (isset($errors['phone_number'])) echo 'error'; ?>" value="<?= $proxy_person['phone_number'] ?>"
               onblur="InputCount('phone_number', 'quarter', 128, 'phone_number_correct', '����� �������� �� ����� ���� ����� �����', '���������� ��������� ����� ��������', '')"
            /><br /><br /><br />

        <input type="submit" name="add" value="��������" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>