import torch
import torch.nn as nn
import torch.optim as optim
import torch.nn.functional as F # Import thêm hàm tính Cosine của PyTorch
import pymysql
import pandas as pd
import numpy as np
import itertools

# ==========================================
# BƯỚC 1: LẤY DỮ LIỆU TỪ MYSQL
# ==========================================
print("Đang kết nối Database và lấy dữ liệu...")
connection = pymysql.connect(host='localhost', user='root', password='', db='evashop')
cursor = connection.cursor()

# Lấy các đơn hàng "Hoàn thành"
query = """
    SELECT c.donhang_id, c.sanpham_id 
    FROM chitietdonhang c
    JOIN donhang d ON c.donhang_id = d.id
    WHERE d.trang_thai = 'Hoàn thành'
"""
cursor.execute(query)
rows = cursor.fetchall()

if not rows:
    print("LỖI: Không có đơn hàng nào ở trạng thái 'Hoàn thành'. Hãy vào bảng 'donhang' sửa lại!")
    exit()

df = pd.DataFrame(rows, columns=['donhang_id', 'sanpham_id'])
max_item_id = df['sanpham_id'].max() + 1 

# Tạo danh sách các cặp mua chung
pairs = []
orders = df.groupby('donhang_id')['sanpham_id'].apply(list)
for items in orders:
    if len(items) > 1:
        pairs.extend(list(itertools.combinations(items, 2)))

if not pairs:
    print("CẢNH BÁO: Các đơn 'Hoàn thành' đều chỉ mua 1 món. Cần ít nhất 1 đơn mua 2 món trở lên!")
    exit()

print(f"-> Tìm thấy {len(pairs)} cặp sản phẩm thực tế. Bắt đầu đưa vào AI học...")

# ==========================================
# BƯỚC 2: MẠNG NEURAL (ĐÃ NÂNG CẤP LÕI TOÁN HỌC)
# ==========================================
class RecommendationModel(nn.Module):
    def __init__(self, num_items, embedding_dim=15): # Tăng số chiều lên 15 cho thông minh hơn
        super(RecommendationModel, self).__init__()
        self.item_emb = nn.Embedding(num_items, embedding_dim)

    def forward(self, item_a, item_b):
        emb_a = self.item_emb(item_a)
        emb_b = self.item_emb(item_b)
        # ÉP AI HỌC TRỰC TIẾP COSINE SIMILARITY (Điểm 10 chất lượng ở đây!)
        return F.cosine_similarity(emb_a, emb_b)

# ==========================================
# BƯỚC 3: HUẤN LUYỆN MÔ HÌNH
# ==========================================
model = RecommendationModel(max_item_id)
# Giảm tốc độ học xuống 0.01 để AI học từ từ, không bị "ngáo"
optimizer = optim.Adam(model.parameters(), lr=0.01)
loss_fn = nn.MSELoss() 

item_a_tensor = torch.tensor([p[0] for p in pairs], dtype=torch.long)
item_b_tensor = torch.tensor([p[1] for p in pairs], dtype=torch.long)
# Target điểm tuyệt đối là 1.0 cho các cặp mua chung
target_score = torch.ones(len(pairs), dtype=torch.float32)

print("-> Đang Training AI ngầm (Epochs: 300)...")
for epoch in range(300): # Tăng vòng lặp lên 300
    optimizer.zero_grad() 
    predictions = model(item_a_tensor, item_b_tensor)
    loss = loss_fn(predictions, target_score)
    loss.backward()
    optimizer.step()
    
print("-> Học xong! Đang xuất kết quả ra Database.")
print("-" * 50)

# ==========================================
# BƯỚC 4: LƯU VÀO DATABASE BẢNG ai_recommendations
# ==========================================
# Dọn sạch bảng cũ để nạp kết quả chuẩn xác nhất của hôm nay
cursor.execute("TRUNCATE TABLE ai_recommendations")

item_weights = model.item_emb.weight.detach().numpy()
unique_items = df['sanpham_id'].unique()
inserted_count = 0

from sklearn.metrics.pairwise import cosine_similarity

for i in unique_items:
    for j in unique_items:
        if i != j:
            vector_i = item_weights[i].reshape(1, -1)
            vector_j = item_weights[j].reshape(1, -1)
            score = cosine_similarity(vector_i, vector_j)[0][0]
            
            # Chỉ lưu các cặp AI cực kỳ tự tin (Điểm > 0.8)
            if score > 0.8:
                insert_sql = "INSERT INTO ai_recommendations (product_id, recommended_product_id, score) VALUES (%s, %s, %s)"
                cursor.execute(insert_sql, (int(i), int(j), float(score)))
                inserted_count += 1
                print(f"  [+] Chốt đơn: Sản phẩm {i} gợi ý {j} | Độ chính xác: {score:.4f}")

connection.commit()
connection.close()

print("-" * 50)
print(f"HOÀN TẤT! Đã đưa {inserted_count} cặp siêu chuẩn vào Database.")