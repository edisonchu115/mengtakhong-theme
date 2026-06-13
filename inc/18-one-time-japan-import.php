<?php
// One-time: Japan products import tool
if (!defined('ABSPATH')) exit;

/* ──────────────────────────────────────────────
   日本產品批量匯入工具（fuzzy match 圖片）
   ────────────────────────────────────────────── */

function mth_japan_products_data() {
    return array(
        array('zh'=>'神戶15 年熟成 拔蘭地（限量版）', 'en'=>'Supreme KOBE Brandy 15year', 'spec'=>'750ml', 'abv'=>'45'),
        array('zh'=>'神戶藍神威士忌', 'en'=>'KOBE Blue Blended Whisky', 'spec'=>'700ml', 'abv'=>'40'),
        array('zh'=>'神戶藍神純麥威士忌', 'en'=>'Kobe Blue Pure Malt Whisky', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本神戶藍神 單一純麥芽威士忌', 'en'=>'Kobe Blue Japan Single Malt Whisky', 'spec'=>'750ml', 'abv'=>'43'),
        array('zh'=>'神戶藍神梅酒威士忌', 'en'=>'Kobe Blue Umeshu Whisky', 'spec'=>'700ml', 'abv'=>'14.5'),
        array('zh'=>'日本神戶藍神 No.11 氈酒', 'en'=>'Kobe Blue No.11 Gin', 'spec'=>'750ml', 'abv'=>'38'),
        array('zh'=>'神戶梅酒白蘭地', 'en'=>'KOBE Umeshu Brandy', 'spec'=>'500ml', 'abv'=>'15'),
        array('zh'=>'神戶梅酒威士忌', 'en'=>'KOBE Umeshu Whisky', 'spec'=>'500ml', 'abv'=>'15'),
        array('zh'=>'神戶梅酒氈酒', 'en'=>'Kobe Umeshu Gin', 'spec'=>'500ml', 'abv'=>'15'),
        array('zh'=>'白鶴蜂蜜花梨酒', 'en'=>'Hakutsuru Honey Karinshu', 'spec'=>'300ml', 'abv'=>'19.5'),
        array('zh'=>'神戶雅瑪氈酒', 'en'=>'KOBE GIN', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本沖繩超熟2003 18 年熟成威士忌', 'en'=>'Chojuku Okinawa Distilled In 2003 Aged 18Years Single Grain', 'spec'=>'700ml', 'abv'=>'40'),
        array('zh'=>'日本沖繩超熟2001 20 年熟成威士忌', 'en'=>'Chojuku Okinawa Distilled In 2001 Aged 20Years Single Grain', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本沖繩 8 年熟成威士忌', 'en'=>'Okinawa Blue Rice Aged 8Years Rice Whisky Distilled In 2013', 'spec'=>'750ml', 'abv'=>'40'),
        array('zh'=>'日本神戶印路葡萄白2019', 'en'=>'Kobe Wine Inji Vineyard 2019', 'spec'=>'750ml', 'abv'=>'11'),
        array('zh'=>'日本神戶精選紅葡萄酒', 'en'=>'Kobe Wine Select', 'spec'=>'720ml', 'abv'=>'12'),
        array('zh'=>'日本信州岩井 極醇威士忌', 'en'=>'Mars Blended Whisky Extra', 'spec'=>'1800ml', 'abv'=>'37'),
        array('zh'=>'白鶴小百合特撰 純米濁酒', 'en'=>'Hakutsuru SAYURI Nigori Sake (Coarse-Filtered Sake)', 'spec'=>'720ml', 'abv'=>'12.5'),
        array('zh'=>'白鶴小百合特撰 純米濁酒', 'en'=>'Hakutsuru SAYURI Nigori Sake (Coarse-Filtered Sake)', 'spec'=>'300ml', 'abv'=>'12.5'),
        array('zh'=>'白鶴特撰 純米吟醸', 'en'=>'Hakutsuru Superipr Sake Junmai Ginjo', 'spec'=>'300ml', 'abv'=>'14.5'),
        array('zh'=>'白鶴特撰 純米吟醸', 'en'=>'Hakutsuru Superipr Sake Junmai Ginjo', 'spec'=>'720ml', 'abv'=>'14.5'),
        array('zh'=>'白鶴特撰 純米吟醸', 'en'=>'Hakutsuru Superipr Sake Junmai Ginjo', 'spec'=>'1800ml', 'abv'=>'14.5'),
        array('zh'=>'白鶴超特撰純米大吟釀 (新白鶴錦)', 'en'=>'HAKUTSURU Junmai Dai Ginjo Hakutsuru-Nishiki', 'spec'=>'720ml', 'abv'=>'15.5'),
        array('zh'=>'白鶴上撰清酒 杯裝', 'en'=>'Hakutsuru Excellent Junmai Sake', 'spec'=>'200ml', 'abv'=>'15'),
        array('zh'=>'白鶴上撰 清酒', 'en'=>'Hakutsuru Excellent Junmai Sake', 'spec'=>'1800ml', 'abv'=>'15'),
        array('zh'=>'白鶴上撰清酒 紙盒裝', 'en'=>'Hakutsuru Excellent Junmai Sake', 'spec'=>'2000ml', 'abv'=>'15'),
        array('zh'=>'白鶴圓圓清酒 紙盒裝', 'en'=>'HAKUTSURU MARU Sake Pack', 'spec'=>'2000ml', 'abv'=>'13'),
        array('zh'=>'白鶴圓圓（純米酒）清酒 紙盒裝', 'en'=>'Hakutsuru Sake Pack MARU Junmai', 'spec'=>'2000ml', 'abv'=>'13.5'),
        array('zh'=>'日本白鶴雫花純米酒', 'en'=>'Hakutsuru Shizuka Junmai', 'spec'=>'500ml', 'abv'=>'9'),
        array('zh'=>'白鶴大吟釀', 'en'=>'Hakutsuru Dai Ginjo', 'spec'=>'1800ml', 'abv'=>'15.5'),
        array('zh'=>'白鶴大吟釀', 'en'=>'Hakutsuru Dai Ginjo', 'spec'=>'720ml', 'abv'=>'15.5'),
        array('zh'=>'白鶴大吟釀', 'en'=>'Hakutsuru Dai Ginjo', 'spec'=>'300ml', 'abv'=>'15.5'),
        array('zh'=>'白鶴大吟釀', 'en'=>'Hakutsuru Dai Ginjo', 'spec'=>'180ml', 'abv'=>'15.5'),
        array('zh'=>'白鶴翔雲超特撰 純米大吟釀', 'en'=>'Hakutsuru Premium Sake Junmai Dai Ginjo SHO-UNE', 'spec'=>'300ml', 'abv'=>'15.5'),
        array('zh'=>'白鶴山田錦特別純米酒', 'en'=>'HAKUTSURU Yamada Junmai Ginjo', 'spec'=>'300ml', 'abv'=>'14.5'),
        array('zh'=>'白鶴桃味濁酒', 'en'=>'HAKUTSURU NIGORI PEACH', 'spec'=>'700ml', 'abv'=>'5'),
        array('zh'=>'白鶴柚子酒', 'en'=>'Hakutsuru Nigori Yuzu', 'spec'=>'700ml', 'abv'=>'9.5'),
        array('zh'=>'白鶴梅酒濁酒', 'en'=>'Hakutsuru Nigori Umeshu', 'spec'=>'700ml', 'abv'=>'9.5'),
        array('zh'=>'白鶴梅酒原酒', 'en'=>'HAKUTSURU Umeshu', 'spec'=>'720ml', 'abv'=>'19.5'),
        array('zh'=>'白鶴梅酒原酒', 'en'=>'HAKUTSURU Umeshu', 'spec'=>'300ml', 'abv'=>'19.5'),
        array('zh'=>'白鶴梅酒原酒', 'en'=>'Hakutsuru Umeshu Plum Wine', 'spec'=>'1800ml', 'abv'=>'19.5'),
        array('zh'=>'白鶴梅酒原酒-3 年熟成', 'en'=>'Hakutsuru Premium Umeshu Genshu 3Year', 'spec'=>'720ml', 'abv'=>'19.5'),
        array('zh'=>'日本白鶴柚子梅酒', 'en'=>'Hakutsuru Hono Plum Yuzu Umeshu Citron Liquor Product', 'spec'=>'500ml', 'abv'=>'9'),
        array('zh'=>'日本白鶴果凍 罐裝 汽泡梅酒', 'en'=>'Hakutsuru buruburu Plum', 'spec'=>'190ml', 'abv'=>'5'),
        array('zh'=>'日本白鶴果凍 罐裝 芒果味', 'en'=>'Hakutsuru Purupuru Sparkling Jelly Mango', 'spec'=>'190ml', 'abv'=>'5'),
        array('zh'=>'日本白鶴果凍 罐裝 蘋果味', 'en'=>'Hakutsuru Purupuru Sparkling Jelly Apple', 'spec'=>'190ml', 'abv'=>'5'),
        array('zh'=>'日本白鶴果凍 罐裝 檸檬清酒味', 'en'=>'Hakutsuru Sparkling Jelly Sake Lemon', 'spec'=>'190ml', 'abv'=>'3'),
        array('zh'=>'日本白鶴果凍 罐裝 桃味', 'en'=>'Hakutsuru Purupuru Sparkling Jelly Peach', 'spec'=>'190ml', 'abv'=>'3'),
        array('zh'=>'日本京姬（匠） 山田錦大吟釀', 'en'=>'Kyohime Yamadanishiki Daiginjo Takumi', 'spec'=>'1800ml', 'abv'=>'15'),
        array('zh'=>'日本京姬（匠） 山田錦大吟釀', 'en'=>'Kyohime Yamadanishiki Daiginjo Takumi', 'spec'=>'720ml', 'abv'=>'15'),
        array('zh'=>'日本京姬(匠)純米大吟釀 磨四割五分', 'en'=>'Kyohime Takumi Junmai Daiginjo', 'spec'=>'1800ml', 'abv'=>'15'),
        array('zh'=>'日本京姬(匠)純米大吟釀 磨四割五分', 'en'=>'Kyohime Takumi Junmai Daiginjo', 'spec'=>'720ml', 'abv'=>'15'),
        array('zh'=>'日本男山國芳乃名取酒 大辛口特別純米', 'en'=>'Otokoyama Kuniyoshi No Natorishu', 'spec'=>'1800ml', 'abv'=>'15'),
        array('zh'=>'日本男山國芳乃名取酒 大辛口特別純米酒', 'en'=>'Otokoyama Kuniyoshi No Natorishu', 'spec'=>'720ml', 'abv'=>''),
        array('zh'=>'日本男山國芳乃名取酒 大辛口特別純米酒', 'en'=>'Otokoyama Kuniyoshi No Natorishu', 'spec'=>'300ml', 'abv'=>''),
        array('zh'=>'白鶴上撰生貯蔵酒', 'en'=>'Hakutsuru Josen Nama Chozoshu', 'spec'=>'500ml', 'abv'=>'13.5'),
        array('zh'=>'京都(京姬)匠大吟釀 無濾過生貯藏', 'en'=>'Takumi Daiginjo Namachozo Muroka-nakatori', 'spec'=>'720ml', 'abv'=>'17'),
        array('zh'=>'京都（匠）純米吟釀原酒 山田錦100%使用', 'en'=>'Takumi Junmaiginjo Genshu Hiyaoroshi', 'spec'=>'720ml', 'abv'=>'17'),
        array('zh'=>'北秋田大吟釀 生貯藏', 'en'=>'Kitaakita Daiginjo Namachozo', 'spec'=>'720ml', 'abv'=>'17'),
        array('zh'=>'北秋田 純米吟釀原酒', 'en'=>'Kitaakita Junmaiginjo Genshu Hiyaoroshi', 'spec'=>'720ml', 'abv'=>'17'),
        array('zh'=>'三佳利果汁飲品 蘋果風味 罐裝', 'en'=>'Sangaria Apple Flavored Soft Drink', 'spec'=>'240g', 'abv'=>''),
        array('zh'=>'三佳利果汁飲品 雜果風味 罐裝', 'en'=>'Sangaria Fruits Mix Flavored Soft Drink', 'spec'=>'240g', 'abv'=>''),
        array('zh'=>'日本三佳利綠茶飲料 抹茶 罐裝', 'en'=>'Sangaria Green Tea With Matcha', 'spec'=>'340g', 'abv'=>''),
        array('zh'=>'白鶴淡雪汽泡清酒', 'en'=>'HAKUTSURU AWAYUKI Sparkling Sake', 'spec'=>'300ml', 'abv'=>'5.5'),
        array('zh'=>'日本韮崎威士忌', 'en'=>'Nirasaki Japanese Whisky', 'spec'=>'700ml', 'abv'=>'40'),
        array('zh'=>'日本三佳利 草莓牛奶飲料 樽裝', 'en'=>'Sangaria Maroyaka Saroyaka Strawberry & Milk', 'spec'=>'500ml', 'abv'=>''),
        array('zh'=>'日本三佳利 香蕉牛奶飲料 樽裝', 'en'=>'Sangaria Maroyaka Banana & Milk', 'spec'=>'500ml', 'abv'=>''),
        array('zh'=>'三佳利炭酸飲料 葡萄味 罐裝', 'en'=>'Sangaria Grape Soda Feeling Refreshed', 'spec'=>'250g', 'abv'=>''),
        array('zh'=>'三佳利炭酸飲料 橙味 罐裝', 'en'=>'Sangaria Orange Sode Feeling Refreshed', 'spec'=>'250g', 'abv'=>''),
        array('zh'=>'三佳利炭酸飲料 蜜瓜味 罐裝', 'en'=>'Sangaria Melon Soda Feeling Rereshed', 'spec'=>'250g', 'abv'=>''),
        array('zh'=>'三佳利炭酸飲料 原味波子汽水 罐裝', 'en'=>'Sangaria Ramune', 'spec'=>'250g', 'abv'=>''),
        array('zh'=>'日本波門崎 調和威士忌 無盒', 'en'=>'Hatozaki Japanese Blended Whisky', 'spec'=>'700ml', 'abv'=>'40'),
        array('zh'=>'日本波門崎純麥芽 調和威士忌 有盒', 'en'=>'Hatozaki Pure Malt Japanese Blended Whisky', 'spec'=>'700ml', 'abv'=>'46'),
        array('zh'=>'日本北海道 克納白葡萄酒2019', 'en'=>'Hokkaido Kerner', 'spec'=>'750ml', 'abv'=>'12'),
        array('zh'=>'日本北海道特選坎貝爾 早期紅葡萄酒2018', 'en'=>'Otaru Tokusen Campbell Early Red', 'spec'=>'720ml', 'abv'=>'8.5'),
        array('zh'=>'日本養命酒製造株式會社 香雫氈酒', 'en'=>'Yomeishu Craft Gin Kanoshizuku Selected Kuromoji', 'spec'=>'300ml', 'abv'=>'37'),
        array('zh'=>'日本養命酒製造株式會社 香雫氈酒', 'en'=>'Yomeishu Craft Gin Kanoshizuku Selected Kuromoji', 'spec'=>'700ml', 'abv'=>'37'),
        array('zh'=>'浜福鶴備前雄町大吟釀', 'en'=>'Hamafukutsuru bizennotyou', 'spec'=>'300ml', 'abv'=>'15'),
        array('zh'=>'浜福鶴備前雄町大吟釀', 'en'=>'Hamafukutsuru bizennotyou', 'spec'=>'720ml', 'abv'=>'15'),
        array('zh'=>'浜福鶴備前雄町大吟釀', 'en'=>'Hamafukutsuru bizennotyou', 'spec'=>'1800ml', 'abv'=>'15'),
        array('zh'=>'日本北海道男山 北稻穗大吟釀', 'en'=>'Otokoyama Kitano Inaho Daiginjo', 'spec'=>'1800ml', 'abv'=>'16'),
        array('zh'=>'兵庫縣產米 浜福鶴純米大吟釀', 'en'=>'Hamafukutsuru zyunbei', 'spec'=>'300ml', 'abv'=>'15'),
        array('zh'=>'浜福鶴純米大吟釀', 'en'=>'Hamafukutsuru zyunbei', 'spec'=>'720ml', 'abv'=>'15'),
        array('zh'=>'浜福鶴純米大吟釀', 'en'=>'Hamafukutsuru zyunbei', 'spec'=>'1800ml', 'abv'=>'15'),
        array('zh'=>'男山特別純米酒', 'en'=>'Tokubetsu Junmai Sushi Booster', 'spec'=>'1800ml', 'abv'=>'15'),
        array('zh'=>'男山特別純米酒', 'en'=>'Tokubetsu Junmai Sushi Booster', 'spec'=>'720ml', 'abv'=>'15'),
        array('zh'=>'男山特別純米酒', 'en'=>'Tokubetsu Junmai Sushi Booster', 'spec'=>'300ml', 'abv'=>'15'),
        array('zh'=>'信州駒之岳2019', 'en'=>'Komagatake Tsunuki Aging 2019 Single Malt', 'spec'=>'700ml', 'abv'=>'56'),
        array('zh'=>'信州駒之岳2019 限量版', 'en'=>'Komagatake Limited edition 2019', 'spec'=>'700ml', 'abv'=>'48'),
        array('zh'=>'日本信州駒之岳 2020 限量版', 'en'=>'Mars Single Malt Komagatake Limited Edition 2020', 'spec'=>'700ml', 'abv'=>'50'),
        array('zh'=>'日本信州駒之岳 2021 限量版 單一麥芽威士忌', 'en'=>'Mars Single Malt Komagatake Edition', 'spec'=>'700ml', 'abv'=>'48'),
        array('zh'=>'日本信州駒之岳 2022 限量版 單一麥芽威士忌', 'en'=>'Mars Single Malt Komagatake 2022 Edition', 'spec'=>'700ml', 'abv'=>'50'),
        array('zh'=>'信州駒之岳2024 年 限量版單一麥芽威士忌', 'en'=>'Mars Single Malt Komagatake 2024 Edition', 'spec'=>'700ml', 'abv'=>'50'),
        array('zh'=>'信州岩井威士忌', 'en'=>'MARS IWAI Japanese Whisky', 'spec'=>'750ml', 'abv'=>'40'),
        array('zh'=>'信州岩井傳統威士忌', 'en'=>'MARS IWAI Tradition Japanese Whisky', 'spec'=>'750ml', 'abv'=>'40'),
        array('zh'=>'日本岩井傳統 雪梨桶威士忌', 'en'=>'Iwai Tradition Blended Sherry Cask Finish', 'spec'=>'700ml', 'abv'=>'40'),
        array('zh'=>'日本信州屋久島壹號威士忌', 'en'=>'Mars The Y.A. #01 Blended Malt Japanese', 'spec'=>'700ml', 'abv'=>'52'),
        array('zh'=>'日本信州屋久島贰號威士忌', 'en'=>'Mars The Y.A#2 Blended Malt', 'spec'=>'700ml', 'abv'=>'49'),
        array('zh'=>'日本信州駒之岳 屋久島單一麥芽威2021', 'en'=>'Mars Whisky Single Malt Komagatake Yakushima', 'spec'=>'700ml', 'abv'=>'56'),
        array('zh'=>'日本厚岸2022-大暑 混合麥芽威士忌', 'en'=>'Akkeshi Blended Taisho', 'spec'=>'700ml', 'abv'=>'48'),
        array('zh'=>'日本厚岸2023-小滿 混合麥芽威士忌', 'en'=>'Akkeshi Blended Shoman', 'spec'=>'700ml', 'abv'=>'48'),
        array('zh'=>'日本厚岸2023-驚螫 單一麥芽威士忌', 'en'=>'Akkeshi Single Malt Keichitsu', 'spec'=>'700ml', 'abv'=>'55'),
        array('zh'=>'日本厚岸2022-大雪 單一麥芽威士忌', 'en'=>'Akkeshi Single Malt Taisetsu', 'spec'=>'700ml', 'abv'=>'55'),
        array('zh'=>'厚岸2023-白露 單一麥芽威士忌', 'en'=>'AKKESHI Single Malt Hakuro whisky', 'spec'=>'700ml', 'abv'=>'55'),
        array('zh'=>'厚岸2023-調酒師之選 波本桶 單一麥芽威士忌', 'en'=>'AKKESHI Single Malt Blenders 2023 whisky', 'spec'=>'700ml', 'abv'=>'58'),
        array('zh'=>'信州津貫2022 年限量版 單一麥芽威士忌', 'en'=>'Mars Tsunuki Single Malt 2022 Edition Japanese', 'spec'=>'700ml', 'abv'=>'50'),
        array('zh'=>'信州津貫2023 年限量版 單一麥芽威士忌', 'en'=>'Mars Tsunuki Single Malt 2023 Edition Japanese', 'spec'=>'700ml', 'abv'=>'50'),
        array('zh'=>'信州津貫泥煤 單一麥芽威士忌', 'en'=>'Mars Tsunuki Single Malt Peated', 'spec'=>'700ml', 'abv'=>'50'),
        array('zh'=>'日本嘉之助(夕陽) 單一麥芽威士忌', 'en'=>'Kanosuke Single Malt Japanese Whisky', 'spec'=>'700ml', 'abv'=>'48'),
        array('zh'=>'日本嘉之助2022 限量版 單一麥芽威士忌', 'en'=>'Kanosuke Single Malt Limited Edition 2022', 'spec'=>'700ml', 'abv'=>'59'),
        array('zh'=>'日本嘉之助2023 限量版 單一麥芽威士忌', 'en'=>'Kanosuke Single Malt Distillery Whisky', 'spec'=>'700ml', 'abv'=>'59'),
        array('zh'=>'信州幸運貓(梅&哈娜) 混合麥芽威士忌', 'en'=>'Mars The Lucky cat Double Individuals May & Hana', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本厚岸(霜降)2024 混合麥芽威士忌', 'en'=>'The Akkeshi Soko Blended Whisky Japanese', 'spec'=>'700ml', 'abv'=>'48'),
        array('zh'=>'信州駒之岳IPA 2022 限量版單一麥芽', 'en'=>'Mars Single Malt Komagatake IPA Cask', 'spec'=>'700ml', 'abv'=>'52'),
        array('zh'=>'信州越百威士忌', 'en'=>'Mars maltage cosmo japanese whisky', 'spec'=>'750ml', 'abv'=>'43'),
        array('zh'=>'信州越百威士忌 雪梨桶', 'en'=>'Mars Maltage COSMO Sherry Cask', 'spec'=>'700ml', 'abv'=>'42'),
        array('zh'=>'戶河內15 年威士忌', 'en'=>'Togouchi Blended Whisky 15Year', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本戶河內 威士忌梅酒', 'en'=>'Togouchi Whisky Umeshu', 'spec'=>'500ml', 'abv'=>'14'),
        array('zh'=>'日本櫻尾臍橙味氈酒', 'en'=>'Sakurao Gin Liqueur Navel Orange', 'spec'=>'700ml', 'abv'=>'23'),
        array('zh'=>'桜尾氈酒', 'en'=>'SAKURAO Japanese Dry Gin', 'spec'=>'700ml', 'abv'=>'47'),
        array('zh'=>'日本櫻尾氈酒 Hamagou 2022', 'en'=>'Sakurao Gin Hamagou 2022 Japanese Dry Gin', 'spec'=>'700ml', 'abv'=>'47'),
        array('zh'=>'日本櫻尾氈酒 Hamagou 2021', 'en'=>'Sakurao Gin Hamagou 2021 Japanese Dry Gin', 'spec'=>'700ml', 'abv'=>'47'),
        array('zh'=>'日本櫻尾 單一麥芽威士忌', 'en'=>'Sakurao Single Malt Japanese Whisky', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本桜島小蜜柑氈酒', 'en'=>'Komasa Gin Japanese Craft Gin', 'spec'=>'500ml', 'abv'=>'45'),
        array('zh'=>'日本櫻島士多啤利氈酒', 'en'=>'Komasa Gin Ichigo Fresh Notes Refined Tones', 'spec'=>'500ml', 'abv'=>'45'),
        array('zh'=>'日本冬日威士忌', 'en'=>'FUYU Blended Japanese Whisky', 'spec'=>'700ml', 'abv'=>'40.5'),
        array('zh'=>'日本冬日威士忌水楢桶', 'en'=>'FUYU Blended Japanese Whisky Mizunara Finish', 'spec'=>'700ml', 'abv'=>'45'),
        array('zh'=>'日本火鳳凰5 年威士忌', 'en'=>'Hinotori Blended Japanese 5Years Whisky', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本十年明 7 年威士忌', 'en'=>'Junenmyo 7Years Whisky', 'spec'=>'700ml', 'abv'=>'46'),
        array('zh'=>'日本（悅）櫻花手工氈酒', 'en'=>'Etsu Sakura Japanese Gin Distilled In Honshu Japan', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本(悅)北海道手工氈酒', 'en'=>'ETSU Handcrafted Hokkaido Original Gin', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本（悅） 限量版 雙重香橙手工氈酒', 'en'=>'ETSU Double Orange Gin', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本（悅） 限量版 雙重柚子手工氈酒', 'en'=>'ETSU Double Yuzu Gin', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'日本東京之夜氈酒', 'en'=>'TOKYO Nights Japanese Gin', 'spec'=>'700ml', 'abv'=>'43'),
        array('zh'=>'養命酒製造 香の森氈酒', 'en'=>'Kanomori Craft Gin', 'spec'=>'700ml', 'abv'=>'47'),
        array('zh'=>'日本富士白蒸餾所 槙羅漢松手工氈酒', 'en'=>'Kozue Wakayama Craft Gin', 'spec'=>'700ml', 'abv'=>'47'),
        array('zh'=>'日本和美人氈酒', 'en'=>'Mars Japanese Gin Wa Bi Gin', 'spec'=>'700ml', 'abv'=>'47'),
        array('zh'=>'養命酒製造蜜柑利口酒', 'en'=>'Yomeishu Honey & Chinese Quince Liqueur', 'spec'=>'250ml', 'abv'=>'14'),
        array('zh'=>'養命酒製造 特調柚子味氈酒', 'en'=>'Yomeishu Craft Gin Cocktail Grape fruits & Herbs', 'spec'=>'300ml', 'abv'=>'22'),
        array('zh'=>'養命酒製造 特調生薑味氈酒', 'en'=>'Yomeishu Craft Gin Cocktail Ginger & Herbs', 'spec'=>'300ml', 'abv'=>'22'),
        array('zh'=>'桜尾限量氈酒', 'en'=>'SAKURAO Limited Japanese Juniper Berry', 'spec'=>'700ml', 'abv'=>'47'),
    );
}

// 正規化字串（移除空格、標點、parentheses 等）
function mth_normalize_str($s) {
    $s = preg_replace('/[\s\(\)（）「」【】\[\]\-_\.,、，\/]+/u', '', $s);
    return mb_strtolower(trim($s));
}

// 將字串入面嘅容量轉成 ml
function mth_extract_ml($s) {
    if (preg_match('/(\d+(?:\.\d+)?)\s*ml/iu', $s, $m)) return (int) round($m[1]);
    if (preg_match('/(\d+(?:\.\d+)?)\s*cl/iu', $s, $m)) return (int) round($m[1] * 10);
    if (preg_match('/(\d+(?:\.\d+)?)\s*l(?![a-z])/iu', $s, $m)) return (int) round($m[1] * 1000);
    if (preg_match('/(\d+)\s*g/iu', $s, $m)) return (int) $m[1];
    return null;
}

// 計算單個產品 vs 圖片嘅匹配分數
function mth_match_score($product, $attachment) {
    $name_zh = $product['zh'];
    $name_en = $product['en'];
    $product_ml = mth_extract_ml($product['spec']);
    $filename = $attachment['basename'];

    $norm_zh = mth_normalize_str($name_zh);
    $norm_en = mth_normalize_str($name_en);
    $norm_file = mth_normalize_str($filename);
    $file_ml = mth_extract_ml($filename);

    // Base: 名稱匹配
    $score = 0;

    // 中文 substring 匹配
    if ($norm_zh && mb_strpos($norm_file, $norm_zh) !== false) {
        $score = 85;
    } elseif ($norm_en && mb_strpos($norm_file, mb_strtolower(preg_replace('/\s+/', '', $name_en))) !== false) {
        $score = 80;
    } else {
        // Levenshtein 相似度
        similar_text($norm_zh, $norm_file, $pct_zh);
        similar_text($norm_en, $norm_file, $pct_en);
        $score = max($pct_zh, $pct_en) * 0.7; // discount
    }

    // 容量匹配 bonus / penalty
    if ($product_ml && $file_ml) {
        if (abs($product_ml - $file_ml) <= 5) {
            $score += 15;
        } else {
            $score -= 40; // 容量唔啱嚴重扣分
        }
    }

    return max(0, min(100, $score));
}

// 讀取 "日本產品" FileBird 資料夾嘅 attachments
function mth_get_japan_folder_attachments() {
    global $wpdb;
    $t = mth_fb_tables();
    if (!$t) return array();

    // 搵 folder（名稱包含 "日本"）
    $folder_id = $wpdb->get_var(
        "SELECT id FROM {$t['folder']} WHERE name LIKE '%日本%' ORDER BY id ASC LIMIT 1"
    );
    if (!$folder_id) return array();

    $ids = $wpdb->get_col($wpdb->prepare(
        "SELECT attachment_id FROM {$t['map']} WHERE folder_id = %d",
        $folder_id
    ));
    if (empty($ids)) return array();

    $items = array();
    foreach ($ids as $id) {
        $file = get_post_meta($id, '_wp_attached_file', true);
        if (!$file) continue;
        $filename = basename($file);
        $items[] = array(
            'id' => (int) $id,
            'filename' => $filename,
            'basename' => pathinfo($filename, PATHINFO_FILENAME),
        );
    }
    return $items;
}

// 計算所有匹配
function mth_japan_calculate_matches() {
    $products = mth_japan_products_data();
    $attachments = mth_get_japan_folder_attachments();

    $results = array();
    foreach ($products as $idx => $p) {
        $best_score = -1;
        $best_att = null;
        foreach ($attachments as $a) {
            $s = mth_match_score($p, $a);
            if ($s > $best_score) {
                $best_score = $s;
                $best_att = $a;
            }
        }
        $results[] = array(
            'index'      => $idx,
            'product'    => $p,
            'attachment' => $best_att,
            'score'      => round($best_score, 1),
        );
    }
    return $results;
}

// Admin submenu
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=mth_product',
        '日本產品匯入',
        '🇯🇵 日本匯入',
        'manage_options',
        'mth-japan-import',
        'mth_render_japan_import'
    );
});

function mth_render_japan_import() {
    if (!current_user_can('manage_options')) return;

    $matches = mth_japan_calculate_matches();
    $attachments = mth_get_japan_folder_attachments();
    $high = array_filter($matches, function($m) { return $m['score'] >= 75; });
    $med  = array_filter($matches, function($m) { return $m['score'] >= 50 && $m['score'] < 75; });
    $low  = array_filter($matches, function($m) { return $m['score'] < 50; });
    ?>
    <div class="wrap">
        <h1>🇯🇵 日本產品批量匯入</h1>
        <p>從「日本產品」資料夾嘅圖片自動匹配並建立產品。</p>

        <?php if (isset($_GET['done'])): ?>
            <div class="notice notice-success">
                <p>✅ 已建立 <?php echo (int) $_GET['done']; ?> 個產品 / 跳過 <?php echo (int) ($_GET['skip'] ?? 0); ?> 個（已存在或低信心）</p>
            </div>
        <?php endif; ?>

        <div style="background:#fff;padding:14px 18px;border:1px solid #ccd0d4;border-radius:4px;margin:14px 0;">
            <h3 style="margin:0 0 8px;">📊 匹配統計</h3>
            <ul style="margin:0;">
                <li>媒體庫「日本」資料夾圖片：<strong><?php echo count($attachments); ?></strong> 張</li>
                <li>產品總數：<strong><?php echo count($matches); ?></strong> 個</li>
                <li>🟢 高信心 (≥75)：<strong><?php echo count($high); ?></strong></li>
                <li>🟡 中信心 (50-75)：<strong><?php echo count($med); ?></strong>（建議手動 review）</li>
                <li>🔴 低信心 (&lt;50)：<strong><?php echo count($low); ?></strong>（唔會建立）</li>
            </ul>
        </div>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" onsubmit="return confirm('確認套用？將建立所有高信心嘅產品（中信心需手動 review 後再 import）。');">
            <input type="hidden" name="action" value="mth_apply_japan_import">
            <?php wp_nonce_field('mth_apply_japan_import'); ?>
            <p>
                <button type="submit" name="apply_high" value="1" class="button button-primary">✅ 建立所有高信心產品（≥75 分）</button>
                <button type="submit" name="apply_all" value="1" class="button" onclick="return confirm('包括中信心嘅都建立？可能會配錯圖。');">⚠️ 連中信心都建立（≥50 分）</button>
            </p>
        </form>

        <h2 style="margin-top:30px;">🟢 高信心匹配（建議建立）</h2>
        <table class="widefat striped">
            <thead><tr><th>產品（中文 / 英文）</th><th>規格</th><th>ABV</th><th>匹配圖片</th><th>分數</th></tr></thead>
            <tbody>
                <?php foreach ($high as $m): $att = $m['attachment']; $p = $m['product']; ?>
                    <tr>
                        <td><strong><?php echo esc_html($p['zh']); ?></strong><br><small><?php echo esc_html($p['en']); ?></small></td>
                        <td><?php echo esc_html($p['spec']); ?></td>
                        <td><?php echo esc_html($p['abv']); ?></td>
                        <td><?php if ($att): ?>
                            <img src="<?php echo esc_url(wp_get_attachment_thumb_url($att['id'])); ?>" style="width:40px;height:40px;object-fit:contain;vertical-align:middle;margin-right:8px;">
                            <code><?php echo esc_html($att['filename']); ?></code>
                        <?php endif; ?></td>
                        <td style="color:#3B6D11;font-weight:600;"><?php echo esc_html($m['score']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        // ─── 手動 review/編輯（中信心 + 低信心）─────────────
        $editable = array_merge(array_values($med), array_values($low));
        if (isset($_GET['manual_done'])):
        ?>
            <div class="notice notice-success" style="margin-top:20px;">
                <p>✅ 手動套用：已建立 <strong><?php echo (int) $_GET['manual_done']; ?></strong> 個 / 跳過 <strong><?php echo (int) ($_GET['manual_skip'] ?? 0); ?></strong> 個</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($editable)): ?>
        <h2 style="margin-top:40px;">🔧 手動編輯（中信心 + 低信心）</h2>
        <p style="color:#666;">改好中文名 / 英文名 / 規格 / ABV / 揀返正確圖片，勾選「建立」嗰格，然後按底嘅「套用」一次過匯入。<br>
        <strong>如果改完之後想揀返新上傳嘅圖：</strong>另開 tab 上傳到媒體庫 → 返呢頁 refresh，新圖會出現喺下拉選單。</p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="mth-manual-form">
            <input type="hidden" name="action" value="mth_apply_japan_manual">
            <?php wp_nonce_field('mth_apply_japan_manual'); ?>

            <p style="margin:14px 0;">
                <button type="button" class="button" id="mth-check-all">☑ 全部勾選</button>
                <button type="button" class="button" id="mth-uncheck-all">☐ 取消全部</button>
                <button type="submit" class="button button-primary" style="margin-left:20px;">✅ 套用已勾選</button>
                <span style="margin-left:14px;color:#666;font-size:13px;">總共 <?php echo count($editable); ?> 個產品可以編輯</span>
            </p>

            <table class="widefat striped" style="margin-top:8px;">
                <thead>
                    <tr style="background:#1C1C1C;color:#D4AF37;">
                        <th style="width:40px;">建立</th>
                        <th style="width:38px;">圖</th>
                        <th style="width:22%;">中文名</th>
                        <th style="width:22%;">英文名</th>
                        <th style="width:90px;">規格</th>
                        <th style="width:60px;">ABV</th>
                        <th>圖片選擇</th>
                        <th style="width:50px;">原分</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($editable as $m):
                        $key = $m['index'];
                        $p = $m['product'];
                        $att = $m['attachment'];
                        $score = $m['score'];
                        $bg = $score >= 50 ? '#fffaef' : '#fdf4f4';
                    ?>
                    <tr style="background:<?php echo $bg; ?>;">
                        <td style="text-align:center;">
                            <input type="checkbox" name="items[<?php echo $key; ?>][use]" value="1" class="mth-row-use">
                        </td>
                        <td>
                            <?php if ($att): ?>
                                <img id="mth-thumb-<?php echo $key; ?>"
                                     src="<?php echo esc_url(wp_get_attachment_thumb_url($att['id'])); ?>"
                                     style="width:32px;height:32px;object-fit:contain;background:#fff;border:1px solid #ddd;border-radius:3px;">
                            <?php else: ?>
                                <div id="mth-thumb-<?php echo $key; ?>"
                                     style="width:32px;height:32px;background:#f5f0e8;border:1px solid #ddd;border-radius:3px;line-height:32px;text-align:center;color:#bbb;">—</div>
                            <?php endif; ?>
                        </td>
                        <td><input type="text" name="items[<?php echo $key; ?>][zh]" value="<?php echo esc_attr($p['zh']); ?>" style="width:100%;padding:4px;"></td>
                        <td><input type="text" name="items[<?php echo $key; ?>][en]" value="<?php echo esc_attr($p['en']); ?>" style="width:100%;padding:4px;"></td>
                        <td><input type="text" name="items[<?php echo $key; ?>][spec]" value="<?php echo esc_attr($p['spec']); ?>" style="width:100%;padding:4px;"></td>
                        <td><input type="text" name="items[<?php echo $key; ?>][abv]" value="<?php echo esc_attr($p['abv']); ?>" style="width:100%;padding:4px;"></td>
                        <td>
                            <select name="items[<?php echo $key; ?>][attachment_id]"
                                    style="width:100%;max-width:340px;padding:4px;"
                                    data-key="<?php echo $key; ?>"
                                    class="mth-att-select">
                                <option value="">— 唔設定圖 —</option>
                                <?php foreach ($attachments as $a): ?>
                                    <option value="<?php echo $a['id']; ?>"
                                            data-thumb="<?php echo esc_attr(wp_get_attachment_thumb_url($a['id'])); ?>"
                                            <?php selected($att && $att['id'] == $a['id']); ?>>
                                        <?php echo esc_html($a['filename']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td style="text-align:center;color:<?php echo $score >= 50 ? '#854F0B' : '#A32D2D'; ?>;">
                            <?php echo esc_html($score); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top:20px;">
                <button type="submit" class="button button-primary button-large">✅ 套用已勾選嘅項目</button>
            </p>
        </form>

        <script>
        (function($){
            $('#mth-check-all').on('click', function(){ $('.mth-row-use').prop('checked', true); });
            $('#mth-uncheck-all').on('click', function(){ $('.mth-row-use').prop('checked', false); });

            // 揀完圖即時更新縮圖預覽
            $('.mth-att-select').on('change', function(){
                var key = $(this).data('key');
                var thumb = $(this).find('option:selected').data('thumb');
                var $el = $('#mth-thumb-' + key);
                if (thumb) {
                    if ($el.is('img')) {
                        $el.attr('src', thumb);
                    } else {
                        $el.replaceWith('<img id="mth-thumb-' + key + '" src="' + thumb + '" style="width:32px;height:32px;object-fit:contain;background:#fff;border:1px solid #ddd;border-radius:3px;">');
                    }
                } else {
                    if ($el.is('img')) {
                        $el.replaceWith('<div id="mth-thumb-' + key + '" style="width:32px;height:32px;background:#f5f0e8;border:1px solid #ddd;border-radius:3px;line-height:32px;text-align:center;color:#bbb;">—</div>');
                    }
                }
                // 自動勾選「建立」嗰格
                $(this).closest('tr').find('.mth-row-use').prop('checked', true);
            });
        })(jQuery);
        </script>
        <?php endif; ?>
    </div>
    <?php
}

// 手動 review / 編輯後嘅匯入處理
add_action('admin_post_mth_apply_japan_manual', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!check_admin_referer('mth_apply_japan_manual')) wp_die('Bad nonce');

    $items = (isset($_POST['items']) && is_array($_POST['items'])) ? $_POST['items'] : array();
    $created = 0; $skipped = 0;

    $term = get_term_by('slug', 'japan', 'mth_product_cat');
    $term_id = $term ? (int) $term->term_id : 0;

    foreach ($items as $key => $item) {
        if (empty($item['use'])) continue;

        $zh   = isset($item['zh'])   ? sanitize_text_field(wp_unslash($item['zh']))   : '';
        $en   = isset($item['en'])   ? sanitize_text_field(wp_unslash($item['en']))   : '';
        $spec = isset($item['spec']) ? sanitize_text_field(wp_unslash($item['spec'])) : '';
        $abv  = isset($item['abv'])  ? sanitize_text_field(wp_unslash($item['abv']))  : '';
        $attachment_id = isset($item['attachment_id']) ? (int) $item['attachment_id'] : 0;

        if (!$zh && !$en) { $skipped++; continue; }

        // 避免重複：title + spec 判定
        if ($zh) {
            $existing = get_posts(array(
                'post_type'   => 'mth_product',
                'title'       => $zh,
                'post_status' => array('publish','draft','pending'),
                'numberposts' => -1,
                'fields'      => 'ids',
            ));
            $dup = false;
            foreach ($existing as $eid) {
                if (get_post_meta($eid, 'spec', true) === $spec) { $dup = true; break; }
            }
            if ($dup) { $skipped++; continue; }
        }

        $post_id = wp_insert_post(array(
            'post_title'  => $zh ?: $en,
            'post_type'   => 'mth_product',
            'post_status' => 'publish',
        ));
        if (is_wp_error($post_id) || !$post_id) { $skipped++; continue; }

        if ($en)   update_post_meta($post_id, 'name_en', $en);
        if ($spec) update_post_meta($post_id, 'spec', $spec);
        if ($abv)  update_post_meta($post_id, 'abv', $abv);
        update_post_meta($post_id, 'source', '代理正貨');
        update_post_meta($post_id, 'origin_country', 'japan');

        if ($term_id) wp_set_object_terms($post_id, array($term_id), 'mth_product_cat');

        if ($attachment_id) set_post_thumbnail($post_id, $attachment_id);

        $created++;
    }

    wp_redirect(admin_url('edit.php?post_type=mth_product&page=mth-japan-import&manual_done=' . $created . '&manual_skip=' . $skipped));
    exit;
});

// 真正執行匯入
add_action('admin_post_mth_apply_japan_import', function() {
    if (!current_user_can('manage_options')) wp_die('No permission');
    if (!check_admin_referer('mth_apply_japan_import')) wp_die('Bad nonce');

    $threshold = isset($_POST['apply_all']) ? 50 : 75;
    $matches = mth_japan_calculate_matches();
    $created = 0; $skipped = 0;

    foreach ($matches as $m) {
        if ($m['score'] < $threshold || !$m['attachment']) { $skipped++; continue; }
        $p = $m['product'];

        // 避免重複：以 title + spec 判定已存在
        $existing = get_posts(array(
            'post_type'   => 'mth_product',
            'title'       => $p['zh'],
            'post_status' => array('publish','draft','pending'),
            'numberposts' => -1,
            'fields'      => 'ids',
        ));
        $dup = false;
        foreach ($existing as $eid) {
            if (get_post_meta($eid, 'spec', true) === $p['spec']) { $dup = true; break; }
        }
        if ($dup) { $skipped++; continue; }

        $post_id = wp_insert_post(array(
            'post_title'  => $p['zh'] ?: $p['en'],
            'post_type'   => 'mth_product',
            'post_status' => 'publish',
        ));
        if (is_wp_error($post_id) || !$post_id) { $skipped++; continue; }

        if (!empty($p['en']))  update_post_meta($post_id, 'name_en', $p['en']);
        if (!empty($p['spec'])) update_post_meta($post_id, 'spec', $p['spec']);
        if (!empty($p['abv']))  update_post_meta($post_id, 'abv', $p['abv']);
        update_post_meta($post_id, 'source', '代理正貨');
        update_post_meta($post_id, 'origin_country', 'japan');

        $term = get_term_by('slug', 'japan', 'mth_product_cat');
        if ($term) wp_set_object_terms($post_id, array((int) $term->term_id), 'mth_product_cat');

        set_post_thumbnail($post_id, (int) $m['attachment']['id']);
        $created++;
    }

    wp_redirect(admin_url('edit.php?post_type=mth_product&page=mth-japan-import&done=' . $created . '&skip=' . $skipped));
    exit;
});

