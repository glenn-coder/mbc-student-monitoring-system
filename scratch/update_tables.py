import os
import glob

base_dir = r'c:\Users\glenn\OneDrive\Desktop\Capstone\mbc-student-monitoring-system\resources\views\admin'
index_files = glob.glob(os.path.join(base_dir, '**', 'index.blade.php'), recursive=True)

thead_pattern = '<thead class="text-xs text-gray-700 bg-gray-100 border-b border-gray-200">'
thead_replace = '<thead class="text-xs text-white bg-blue-600 border-b border-blue-700">'

tr_pattern = '<tr class="bg-white border-b hover:bg-gray-50 {{ $loop->even ? \'bg-gray-50\' : \'\' }}">'
tr_replace = '<tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? \'bg-indigo-50\' : \'\' }} transition-colors">'

for file_path in index_files:
    if 'audit_logs' in file_path:
        continue
        
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
        
    if thead_pattern in content or tr_pattern in content:
        content = content.replace(thead_pattern, thead_replace)
        content = content.replace(tr_pattern, tr_replace)
        
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Updated {file_path}')
