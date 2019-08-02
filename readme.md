JadeClass 考勤管理系统
===
关于
---
本系统根据 JadeClass 公司实际情况定制，使用 Laravel 开发，目的是能够提高 JadeClass 人事行政部门每个月进行的考勤统计效率以及准确性。目前本项目基本功能已经完成。

## 8月2日更新日志
1. 考勤记录中可以补录多日请假。
2. 优化提交增补操作后的返回路径。

## 基本功能介绍
### 员工部分
#### -员工信息管理
<br>- 可以新增员工基础信息，如员工编号，姓名，英文名，部门，职位，入职日期，一周排班，工作经历，银行卡号等。
<br>- 信息一旦录入成功，只有部分信息可以更改。不可更改的信息有：员工姓名，英文名，编号。
<br>- 系统将会根据员工入职日期和工作经历计算出工作年数以及本年度剩余年假。（目前实习生和兼职没有年假）
<br>- 年假以及工作年数将会每年进行一次更新。工作年数小于十年每年有5天年假，大于等于10年每年有10天年假，大于等于20年每年有15天年假。外教每年至少有10天年假。每年有未使用完的年假将累计到下一年度。年假数据无法直接更改，只能通过请假管理对年假进行增减。
<br>- 员工信息录入后无法删除，只能进行离职操作，可以在离职员工表中查看离职员工信息。因此，录入员工数据时关键数据（姓名，编号，英文名）**务必核对无误**，避免计算考勤时产生错误或数据缺失。
<br>- 兼职员工新增页可以不填写年假，工作经历等信息
<br>- 修改员工排班时要求填写生效日期，新的排班在生效日期（包含）之后有效
#### -请假信息管理
<br>- 可根据不同请假类型进行添加。
<br>- 年假和调休必须被批准。
<br>- 有剩余年假的员工才能正常申请年假。
<br>- 调休假必须在剩余调休时间的情况下才能正常添加。
<br>- 请假时长会根据情况相应减少1小时午休时间
#### -加班信息管理
<br>- 可根据不同加班类型进行添加。
<br>- 调休加班必须被批准。
<br>- 加班时长会根据情况相应减少1小时午休时间
#### -节假日调休管理
<br>- 添加本年度法定调休情况，以便计算考勤记录时考虑到节假日情况。
#### -考勤信息管理
<br>- 此功能是本系统核心功能，必须遵循操作步骤，否则最终结果会出现错误。
<br>- 导入原始表成功后，系统将会直接计算当月考勤总数据。
<br>- 可以依据英文名-年-月查询员工当月考勤汇总（英文名可不填）。
<br>- 员工当月应上班工时，实际上班工时，迟到早退次数，加班请假时长，考勤是否异常等数据将在考勤汇总中体现。
<br>- 若汇总表中员工考勤报异常，则说明该员工当月至少有一条考勤记录异常。此时应该查看员工详细考勤数据，进行异常处理。
<br>- 员工详细考勤记录中，包含了该自然日的类型（休息，上班）、员工应上下班时间及时长、实际上下班时间及时长、当日加班请假情况等。
<br>- 员工当日考勤如有异常，可使用“更改异常”、“补打卡”、“增补工时”三种方式修复异常情况。
<br>- 判断一条考勤记录是否异常的原则是：实际工时+(请假时长)-(加班时长)+(增补时长)>=(应该工时-5分钟)，即最大容忍五分钟的少工时情况出现。
<br>- 迟到、早退分钟数会记录在案，但如果工时足够，不会计入该月考勤汇总中。
<br>- 考勤信息可以根据员工类别分别导出
<br>- 如果请假或者加班记录未在考勤导入前填写，可以在考勤界面或者请假加班界面补增

* 补打卡：如果员工上班或下班没有打卡，可进行补打卡操作。
* 增补工时：没有请假记录，但缺少工时的考勤记录可进行增补工时操作。可能增补工时的原因例如：地铁故障导致的迟到，员工哺乳期的提早下班等。目前仅支持对首尾最多两段工作时间的增补。
* 更改异常：如果以上操作均无法使异常状态修复，可强行更改异常情况。此功能推荐使用于对于迟到早退异常的修复。更改异常后，汇总表迟到早退次数不会减少。

#### -表单解释
**因考勤汇总表中列数较多，下面对其中几列的含义进行解释：**
<br>- 总应: 员工当月应该工作总时长 (由每天应该工作时长累加得出)
<br>- 总实: 员工当月实际工作总时长 (由每天实际工作时长累加得出)
<br>- 总基本: 员工当月基本工作总时长 (由每天基本工作时长累加得出。每天实际工时减去加班工时的差值如果大于应工时，每天基本工时等于应工时，否则等于差值。例如: 应工时8小时,实工时7.50小时,那么当日基本工时为7.50小时;应工时8小时,实工时8.50小时,那么当日基本工时为8小时)
<br>- 总额外: 如果在非工作时间工作,且无加班记录的,系统将判定为额外工时（调休加班，带薪加班在总加班工时中体现）
<br>- 总加班: 总加班第一项为调休加班时长,第二项为带薪加班时长。
<br>- 差值: 总基本-总应, 理论上不大于零。差值为少工作的时长。
<br>- 总增补: 员工当月因为特殊原因造成的缺少工时。不计入迟到或早退中。

### 老师部分
#### -老师信息管理
<br>- 通过在输入框中输入员工英文名，从员工列表中添加老师。可以同时添加多位。
<br>- 通过新增学期来定义新学期名称及开始结束时间
<br>- 选择适当学期，管理该学期上课老师信息
<br>- 关联课程选项可以为老师关联已选择学期的课程。支持多个课程同时关联。关联完成后，系统将自动计算该学期老师每月实际排课数据，并调取每月应排课数据
<br>- 可在详情页中查询老师该学期排课代课情况
<br>- 离职老师后，老师不再出现在后续学期中，但员工身份还未离职。所以推荐使用员工页离职功能一键离职老师及员工两个身份
#### -课程信息管理
<br>- 选择相应学期添加课程。添加一节暑期课程默认添加一、三、五三节课。可以在新增课程时分配上课老师，也可以在老师页关联课程。
<br>- 编辑课程仅仅是更新课程时间、教室、名称等信息，并需要填写生效日期。在此日期（包括）之后，如果课程时间调整，其他功能页将按照新时间读取上课信息。
<br>- 更换老师功能支持在学期中调整上课老师，需要填写生效日期。
<br>- 只支持删除没有换课代课记录的课程。删除课程后，老师排课数据也将相应减少。
#### -代课信息管理
<br>- 新增代课时如果不填写代课老师，代表该节课老师缺课。
#### -换课信息管理
<br>- 单节课更换上课时间可以点击"新增换课"
<br>- 当日所有课换课可点击"一键换课"
<br>- 换课后老师原上课月数据和换课上课月应上课时长会根据情况调整
#### -上课考勤查询
<br>- 支持单月数据和多月数据的查询，根据查询月的代课、换课、应上课、实际排课数据计算出结果。（查询月需要在学期内）
<br>- 单月数据查询时不体现加班折算数据，多月查询时体现。（加班数据获取的范围是整个学期）
<br>- 导出考勤数据可以分为全职和兼职老师

## 说明
**本系统部分使用限制和前提在此说明，首次使用请务必阅读：**
##### -录入员工数据
<br>- 员工英文名必须和打卡机上录入的英文名完全相同，包括大小写。
<br>- 本月排班请在上月考勤计算完成后进行修改，否则将影响上月考勤结果。
<br>- 员工英文名必须与考勤机中英文名**完全**一致，否则考勤记录将导入失败。
<br>- 录入2019年以前入职员工或者年假时长已经发生变动的员工时，请务必填写年假时长，否则系统将默认年假未使用。后期在职员工数据录入完成并且系统投入使用后，将限制入职日期可填写范围为本年度。
<br>- 对于每周上班天数变动的员工，建议填写排班时所有天数都填写上下班时间，避免基本工时计算不到的情况。
<br>- 兼职员工可以不填写员工编号。但为了系统内部查询方便，已自动为每个新建员工分配创建时的时间戳作为编号，长度为十位。

##### 请假信息管理
<br>- 请假时长计算仅计算缺少的工时。为了防止缺少工时与课时的重复计算，对于老师的请假，如果含有周六周日等老师排课却不排班的日期，请假时长不计算课时。需要管理员在上课管理中添加该请假时间段的代课缺课或换课信息。

##### -导入考勤表
<br>- 在导入原始考勤数据前，最好确保全部员工当月请假、加班记录已经录入核实，并已经添加了当月节假日调休情况。
<br>- 考勤信息计算必须使用考勤原始表（标准报表或考勤月报），即直接从考勤机获取的表格。否则考勤记录将录入错误。
<br>- 考勤记录中加班、请假工时按照加班、请假申请的时长为准。
<br>- 考勤表一旦导入，数据将不可删除。点击导入考勤表按钮后，请耐心等待一分钟左右，勿多次点击，以免造成数据混乱。导出同理。
<br>- 汇总表可以进行导出，目前仅支持当月汇总以及当月员工考勤同时导出功能。
<br>- 直接从考勤机中拉取的"考勤月报"文件格式有问题，直接上传到考勤系统会报错。请将考勤月报另存为".xls"或".xlsx"文件后再上传。
<br>- 由于"考勤月报"数据表中兼职助教英文名并非英文全名，考勤系统录入数据时会根据中文名查找系统内员工数据，所以在员工信息中请务必填写中文名。
<br>- 请一次性导入整月数据，否则后续日期的数据将无法导入。

##### -老师信息管理
<br>- 老师的应上课信息计算规则：应上课 = 当月总应上班 - 当月排班；学期开始周结束周如果不完整，会从当周礼拜一开始计算当周应上班，减去当周排班，得出应上课时长。
<br>- 如果课程时间发生变动，每月实际排课数据将会依据变动时间及变动生效时间更新
<br>- 总缺课和总代课时长是该学期该老师的累计数据
<br>- 老师实际排课课时计算不考虑节假日调休情况。由于节假日调休造成的换课请到换课管理中另行添加。

##### -课程信息管理
<br>- 如果课程时间或者代课老师有变更，将在编辑信息和更换老师页面体现历史记录

##### -老师上课考勤管理
<br>- 老师应排课、实际排课从老师信息中读出；实际上课时长根据代课、换课信息计算得出，如果没有换课代课信息，则默认已经上课；缺课信息由应上课减去实际上课得出
<br>- 加班时长折算：除了调休加班外，带薪（正常）和测试加班均可以折算补缺少课时。带薪时长1:1，测试时长1:1.2
<br>- 如果漏录换课代课信息，直接在相应功能界面新增即可，再次查询时将更新查询结果

##### 操作流程的说明
<br>- 由于节假日调休对实际上班上课情况有影响，`请优先录入当月的节假日调休情况`，再录入请假加班记录和导入考勤记录，否则数据计算将出现错误并且不可修改。
<br>- 一旦考勤记录导入成功，先前已经存在的请假加班记录的修改`无法在已有的考勤记录上更新`，如有错误，请在加班请假管理页删除记录后重新录入。
<br>- 在当月考勤记录中补录请假加班、补打卡、补工时等操作一旦新建完成将`不可修改`，请仔细核对后提交。加班请假记录如有错误，请在加班请假管理页删除后重新录入。

## 使用环境要求
- 推荐使用Chrome浏览器、360浏览器或搜狗浏览器。请勿使用系统自带浏览器。

## 开发运行环境要求
-   PHP 7.1.3+
-   Laravel 5.8
-   Phpspreadsheet 1.7+
