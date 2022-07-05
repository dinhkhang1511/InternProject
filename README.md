**Sử dụng Laravel Framework để xây dựng module quản lý sản phẩm** => Branch master
- Sử dụng migrate để quản lý schema database, schema.
- Sử dụng query builder để thực hiện các truy vấn trong ứng dụng
- Có đầy đủ các chức năng List product, product detail, delete product, update product
- Dùng blade view để hiển thị các thông tin
- Các chức năng như sau:
- List product gồm các chức năng sau: 
- List ra các product bao gồm phân trang
- Có thể filter theo: status product (pending, approve, reject),  title product,...
- Có phần summary thể hiện bao nhiêu product cho từng status

=> Branch artisanCommand
- Tạo một command để tạo csv từ table products của database. Dữ liệu backup được lưu vào file csv và upload lên aws (update lên AWS tùy chọn)
- Định dạng của file csv 
- id,name,status,pricing 
- 1,Title 1,pending,12000
- 2,Title 2,approve,22000
- Lưu ý: Tính toán đến việc dữ liệu của table lớn, không nên query ra all data trên 1 table mà nên lấy theo limit

Bài tập shopify: nên làm tiếp trên source đã học => Branch shopify ( chưa xong)
.....

