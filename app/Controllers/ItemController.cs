using app.Data;
using app.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace app.Controllers
{
    [Route("api/[controller]")]
    [ApiController]

    public class ItemController : ControllerBase
    {
        private readonly DataContext _context;

        public ItemController(DataContext context)
        {
            _context = context;
        }

        [HttpPut("{id}")]
        public async Task<IActionResult> UpdateItemAsync(int id, string nameItem, int quantityItem)
        {
            try
            {
                var item = await _context.TB_Item.FindAsync(id);
                if (item == null)
                    return NotFound();

                item.NameItem = nameItem;
                item.QuantityItem = quantityItem;

                int affectedRows = await _context.SaveChangesAsync();
                return Ok(affectedRows);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        [HttpGet]
        public async Task<IActionResult> GetItemByIDAsync(int id)
        {
            try
            {
                var item = await _context.TB_Item.FindAsync(id);
                if (item == null)
                    return NotFound();
                return Ok(item);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        [HttpGet]
        public async Task<IActionResult> GetNumberItens()
        {
            try
            {
                var items = await _context.TB_Item.ToListAsync();
                if (items == null)
                    return NotFound();

                return Ok(items.Count);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }
        
        
        [HttpGet]
        public async Task<IActionResult> GetAllItens()
        {
            try
            {
                List<Item> list = await _context.TB_Item.ToListAsync();
                return Ok(list);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        // [ApiExplorerSettings(IgnoreApi = true)]
        [HttpGet("/decreaseItemQuantity/{id}")]
        public async Task<IActionResult> DecreaseItemQuantity(int id)
        {
            try
            {
                var item = await _context.TB_Item.FindAsync(id);
                if (item == null)
                    return NotFound();

                item.QuantityItem = item.QuantityItem>0 ? item.QuantityItem - 1 : 0;
                int affectedRows = await _context.SaveChangesAsync();
                return Ok(affectedRows);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }

        // public async int IntentoryLogic(){
            
        // }
    }
}
