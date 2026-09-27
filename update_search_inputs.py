import os
import glob
import re

views_dir = r"c:\Users\glenn\OneDrive\Desktop\Capstone\mbc-student-monitoring-system\resources\views"
pattern = os.path.join(views_dir, "**/*.blade.php")
files = glob.glob(pattern, recursive=True)

search_input_pattern = re.compile(
    r'(<input[^>]+name="search"[^>]*)(>)'
)

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Check if we need to replace
    # Only replace if it doesn't already have oninput
    if 'name="search"' in content and 'type="text"' in content and 'oninput=' not in content:
        # Actually, let's just find the exact string to replace
        
        # A more specific replace:
        new_content = re.sub(
            r'(<input\s+type="text"\s+name="search"[^>]*)(>)',
            r'\1 oninput="if(this.value === \'\') { this.form.submit(); }"\2',
            content
        )
        
        if new_content != content:
            with open(file, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f"Updated {file}")
