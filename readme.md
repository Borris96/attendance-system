# JadeClass 考勤管理系统
## 关于
本系统根据 JadeClass 公司实际情况定制，使用 Laravel 开发，目的是能够提高 JadeClass 人事行政部门每个月进行的考勤统计效率以及准确性。目前本项目正在开发之中。

## 运行环境要求
-   PHP 7.1+
-   Laravel 5.8

## 基本功能介绍
### 员工信息管理
<br> 可以新增员工基础信息，如员工编号，姓名，英文名，部门，职位，入职日期，一周排班，工作经历，工资卡号等。
<br> 信息一旦录入成功，部分信息无法更改。可以更改的信息有：部门，职位，一周排班，工作经历，银行卡号。
<br> 系统将会根据员工入职日期和工作经历计算出工作年数以及本年度剩余年假。（目前实习生和兼职没有年假）
<br> 年假以及工作年数将会每年进行一次更新。工作年数小于十年每年有5天年假，大于等于10年每年有10天年假，大于等于20年每年有15天年假。每年有未使用完的年假将累计到下一年度。年假数据无法直接更改，只能通过请假管理对年假进行加减。
<br> 员工信息录入后无法删除，只能进行离职操作，可以在离职员工表中查看离职员工信息。因此，录入员工数据时关键数据（姓名，编号，英文名）务必核对无误，避免计算考勤时产生错误或数据缺失。
### 请假信息管理
<br> 可根据不同请假类型进行添加。
<br> 年假和调休必须被批准。
<br> 有剩余年假的员工才能正常申请年假。
<br> 调休假必须在剩余调休时间的情况下才能正常添加。
<br> 请假时长会根据情况相应减少1小时午休时间
### 加班信息管理
<br> 可根据不同加班类型进行添加。
<br> 调休加班必须被批准。
<br> 加班时长会根据情况相应减少1小时午休时间
### 节假日调休管理
<br> 添加本年度法定调休情况，以便计算考勤记录时考虑到节假日情况。
### 时薪信息管理 （由于后期开发中客户需求变更，此功能已被弃用）
<br> 添加不同时薪类型，每月工资计算时将会使用到。
### 考勤信息管理
<br> 此功能是本系统核心功能，必须遵循操作步骤，否则最终结果会出现错误。
<br> 考勤信息计算必须使用考勤原始表（标准报表），即直接从考勤机获取的表格。否则考勤记录将录入错误。
<br> 在导入原始考勤数据前，首先需要确保当月的全部员工请假、加班记录已经录入，并已经添加了当月节假日调休情况。
<br> 随后导入原始员工考勤表。如导入成功，系统将会直接计算当月考勤总数据。
<br> 可以依据英文名-年-月查询员工当月考勤汇总（英文名可不填）。
<br> 员工当月应上班工时，实际上班工时，迟到早退次数，加班请假时长，考勤是否异常等数据将在考勤汇总中体现。
<br> 若汇总表中员工考勤报异常，则说明该员工当月至少有一条考勤记录异常。此时应该查看员工详细考勤数据，进行异常处理。
<br> 员工详细考勤记录中，包含了该自然日的类型（休息，上班）、员工应上下班时间及时长、实际上下班时间及时长、当日加班请假情况等。
<br> 员工当日考勤如有异常，可使用“更改异常”、“补打卡”、“增补工时”三种方式修复异常情况。而从原始表格导入的原始数据将会被严格保护。
<br> 判断一条考勤记录是否异常的原则是：实际工时+请假时长-加班时长>=(应该工时-5分钟)，即允许五分钟的少工时情况出现。
<br> 迟到、早退分钟数会记录在案，但如果工时足够，不会计入该月考勤汇总中。
* 补打卡：如果员工上班或下班没有打卡，可进行补打卡操作。
* 增补工时：没有请假记录，但缺少工时的考勤记录可进行增补工时操作。可能增补工时的原因例如：地铁故障导致的迟到，哺乳期的提早下班等。
* 更改异常：如果以上操作均无法使异常状态修复，可强行更改异常情况。此功能适用于对于迟到早退异常的修复。更改异常后，迟到早退次数将不会减少。

## 后续开发
-   6月21日开始将对特殊员工信息录入以及相关算法进行完善（进行中）
-   生成的数据表单导出功能
-   直接导入员工一周排班表
-   7月份将开始对老师上课考勤板块进行开发
