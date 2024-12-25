# laravel

### DB Test
1. 請寫出查詢語句 (SQL)
```
SELECT 
    o.bnb_id AS bnb_id,
    b.name AS bnb_name,
    SUM(o.amount) AS may_amount
FROM 
    orders o
JOIN 
    bnbs b
ON 
    o.bnb_id = b.id
WHERE 
    o.currency = 'TWD' 
    AND o.created_at >= '2023-05-01 00:00:00'
    AND o.created_at < '2023-06-01 00:00:00'
GROUP BY 
    o.bnb_id, b.name
ORDER BY 
    may_amount DESC
LIMIT 10;
```
2. 優化方式
+ 查看Excution plan
+ 查看orders是否有建立Index，特別是currency和created_at這兩個拿來當query條件的
+ 如果orders很大，可以根據created_at建立partition
+ 嘗試用with或subquery的方式，先將orders的資料取出，再做join
### API
**SOLID 原則應用**：
+ 單一職責原則 (SRP)：每個類別都有單一的職責
   * OrderController 處理 HTTP 請求
   * OrderService 協調驗證和轉換流程
   * OrderValidator 專門處理驗證邏輯
   * OrderTransformer 專門處理轉換邏輯

+ 開放封閉原則 (OCP)：通過使用介面和依賴注入，使系統易於擴展，可以輕易添加新的驗證規則或轉換邏輯
   * 通過使用 Service 和 Transformer 來實現業務邏輯和數據轉換，不改動既有程式碼的情況下，輕鬆擴展新的格式檢查或轉換邏輯。

+ 依賴反轉原則 (DIP)：高層模組不依賴低層模組，都依賴於抽象
   * 使用依賴注入來注入相依性
   * Controller 透過依賴注入來調用 Service，Service 又依賴於 Validator 和 Transformer。這樣做遵循了依賴倒轉原則，達到高層模組（Controller）依賴於抽象層（Service、Validator、Transformer）。
**設計模式應用**：
+ Strategy Pattern
    * OrderValidator 和 OrderTransformer 作為獨立策略類別
    * 可以輕易添加新的驗證規則或幣值轉換策略
+ Chain of Responsibility
    * 請求處理流程：Controller -> FormRequest -> Service -> Validator -> Transformer
    * 每個環節負責特定任務並決定是否傳遞給下一環(訂單資料依序經過輸入驗證、格式檢查、幣值轉換)
+ Factory Method Pattern (透過 Laravel DI Container)
    * 在 Controller 中注入 OrderService
    * 在 Service 中注入 Validator 和 Transformer
+ Repository Pattern (為未來擴展預留)
    * 當需要添加數據持久化時，可以輕易實現 Repository 層
    * 保持 Service 層邏輯不變
+ Template Method Pattern
    * OrderValidator 定義驗證流程骨架
    * containsNonEnglish 和 isCapitalized 作為具體實現方法
