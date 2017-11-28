import random
import csv
import datetime

orderList, masterOrderList, recordList = [],[],[]
index,masterIndex, recordIndex =1,1,1

quanlist = ['1']*5 + ['2','3']
foodlist=['BG001','BG002','BG003','BG004','BG005','BG006','BG007','BG008','BG009','BG010','BG011','PZ001','PZ002','PZ003','PZ004','PZ005','PZ006','PZ007','PZ008','PZ009','PZ010','SD001','SD002','SD003','SD004','SD005','SD006','SD007','SD008','SD009','SD010','SD011','CK001','CK002','CK003','CK004','CK005','CK006','CK007','CK008','CK009','DR001','DR002','DR003','DR004','DR005','DR006','DR007','DR008','DR009','DR010','DR011','DS001','DS002','DS003','DS004','DS005','DS006','DS007','DS008','DS009','DS010']
pricelist=[30,20,24,22,12,12,12,12,18,15,30,90,90,100,100,80,60,70,90,90,90,10,12,10,15,10,18,16,20,18,14,12,30,30,40,25,25,22,30,60,50,10,10,10,10,12,13,13,13,15,15,10,18,18,18,24,15,15,15,10,10,15]
staffList=['1','2','3','7','8']
user=["emily","haha","gc28","CW2000","bwong"]
user_key=['29ds4k','7dck3i','2x3x4x','sejicnq','12583k']
admin=["katherine"]
admin_key=['ka123']

tableList=['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12','T13','T14','T15','T16','T17','T18','T19','T20']


# for day in range(120):
#     count = random.randint(50,300)
#     master, recordTotal = 0,0
#     randTimeList = [random.randint(0,46800) for i in range(count)]
#     randTimeList.sort()
#     for _ in range(count): #each day loop master orders
#         times = random.randint(1,3)
#         total=0
#         for _ in range(times): # each master order loop orders
#             food_pos = random.randint(0,len(foodlist)-1)
#             total+=pricelist[food_pos]
#             order = [str(index),random.choice(quanlist),str(masterIndex),foodlist[food_pos],str(pricelist[food_pos])]
#             orderList.append(order)
#             index+=1
#         d_date_time = datetime.datetime(2017,11,6,8,0,0) + datetime.timedelta(days=day, seconds=(randTimeList[master]) )
#         getTime = d_date_time.strftime("%H:%M:%S")
#         getDate = d_date_time.strftime("%Y-%m-%d")
#         # masterOrderID,price,payment,change,staffID,ReportID,TableNo,checkoutTime,checkoutDate
#         masterRow = [str(masterIndex),str(total),str(total),'0',random.choice(staffList),str(day+1),random.choice(tableList),getTime,getDate]
#         masterOrderList.append(masterRow)
#         master += 1
#         masterIndex += 1
#         recordTotal += total
#     # reportID, income, DATE, count, staffID
#     recordRow = [str(recordIndex),str(recordTotal),getDate,str(master),str(random.choice(staffList))]
#     recordList.append(recordRow)
#     recordIndex+=1
#
# with open('order.csv','w') as f:
#     w = csv.writer(f)
#     title=[['orderID','quantity','MasterOrderID', 'FoodID']]
#     w.writerows(title)
#     w.writerows(orderList)
#
# with open('masterorder.csv','w') as f:
#     w = csv.writer(f)
#     w.writerows([['masterOrderID','price','payment','change','staffID','ReportID','TableNo','checkoutTime','checkoutDate']])
#     w.writerows(masterOrderList)
#
# with open('report.csv','w') as f:
#     w = csv.writer(f)
#     w.writerows([['reportID', 'income', 'DATE', 'count', 'staffID']])
#     w.writerows(recordList)

with open('account.sql','w') as f:
    for i,j in zip(user,user_key):
        f.write("CREATE USER '%(name)s'@'localhost' IDENTIFIED BY '%(password)s';\
            GRANT ALL ON Restaurant.masterorder TO '%(name)s'@'localhost';\
            GRANT SELECT, UPDATE(quantity) ON Restaurant.menu TO '%(name)s'@'localhost';\
            GRANT ALL ON Restaurant.`order` TO '%(name)s'@'localhost';\
            GRANT ALL ON Restaurant.report TO '%(name)s'@'localhost';\
            GRANT SELECT, UPDATE(Available) ON Restaurant.`table` TO '%(name)s'@'localhost';\
            GRANT SELECT(StaffID,password), UPDATE(PassWord) ON Restaurant.staff TO '%(name)s'@'localhost';\n" %{'name':i, 'password':j})
    for i,j in zip(admin,admin_key):
        f.write("CREATE USER '%(name)s'@'localhost' IDENTIFIED BY '%(password)s';\
        GRANT ALL ON *.* TO '%(name)s'@'localhost' WITH GRANT OPTION;\
        GRANT CREATE USER ON *.* TO '%(name)s'@'localhost' WITH GRANT OPTION;\n" %{'name':i, 'password':j} )
    f.write("FLUSH PRIVILEGES;\n")


