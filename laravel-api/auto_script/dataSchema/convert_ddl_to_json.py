import json
from simple_ddl_parser import DDLParser

# Đường dẫn file SQL (cùng cấp thư mục)
sql_file = 'DDL.sql'

# Đọc nội dung file SQL
with open(sql_file, 'r') as f:
    ddl = f.read()

# Parse DDL với mode PostgreSQL
result = DDLParser(ddl).run(output_mode='postgres')

# Convert kết quả sang JSON string (đẹp hơn với indent)
json_schema = json.dumps(result, indent=4)

# In ra console để kiểm tra
print(json_schema)

# Lưu ra file schema.json
with open('schema.json', 'w') as f:
    f.write(json_schema)

print("Convert hoàn tất! Kết quả lưu tại schema.json")

####################################################################################
# Truy cập container này và cd đến folder này. Run `python3 convert_ddl_to_json.py``